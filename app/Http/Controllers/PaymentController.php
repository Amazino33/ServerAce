<?php

namespace App\Http\Controllers;

use App\Models\Gig;
use App\Models\GigApplication;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Create payment intent for accepting an application
     */
    public function createIntent(Request $request, GigApplication $application)
    {
        $request->validate([
            'gig_id' => 'required|exists:gigs,id',
        ]);

        $client = auth()->user();
        $gig = Gig::findOrFail($request->gig_id);

        // Verify client owns the gig
        if ($gig->user_id !== $client->id) {
            return response()->json([
                'success' => false,
                'error' => 'You do not have permission to accept this application.',
            ], 403);
        }

        // Verify application belongs to this gig
        if ($application->gig_id !== $gig->id) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid application for this gig.',
            ], 400);
        }

        // Verify application is still pending
        if ($application->status !== 'pending') {
            return response()->json([
                'success' => false,
                'error' => 'This application has already been processed.',
            ], 400);
        }

        // Verify freelancer is onboarded
        $freelancer = $application->freelancer;
        if (!$freelancer->stripe_onboarded || !$freelancer->stripe_account_id) {
            return response()->json([
                'success' => false,
                'error' => 'This freelancer has not set up their payment account yet.',
            ], 400);
        }

        // Verify gig is not already paid for
        if ($gig->payment_completed) {
            return response()->json([
                'success' => false,
                'error' => 'Payment has already been made for this gig.',
            ], 400);
        }

        try {
            // Create payment intent
            $result = $this->paymentService->createPaymentIntent(
                $gig,
                $client,
                $freelancer,
                $application->proposed_price
            );

            if ($result['success']) {
                Log::info('Payment intent created', [
                    'gig_id' => $gig->id,
                    'client_id' => $client->id,
                    'freelancer_id' => $freelancer->id,
                    'amount' => $application->proposed_price,
                    'payment_intent_id' => $result['payment_intent_id'],
                ]);

                return response()->json([
                    'success' => true,
                    'client_secret' => $result['client_secret'],
                    'payment_id' => $result['payment']->id,
                    'amount' => $application->proposed_price,
                    'platform_fee' => $result['payment']->platform_fee,
                    'freelancer_amount' => $result['payment']->freelancer_amount,
                ]);
            }

            Log::error('Failed to create payment intent', [
                'error' => $result['error'],
                'gig_id' => $gig->id,
            ]);

            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 500);

        } catch (\Exception $e) {
            Log::error('Payment intent creation error: ' . $e->getMessage(), [
                'gig_id' => $gig->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing your payment. Please try again.',
            ], 500);
        }
    }

    /**
     * Handle successful payment and move to escrow
     */
    public function success(Request $request)
{
    $paymentIntentId = $request->query('payment_intent');

    \Log::info('SUCCESS PAGE HIT', [
        'payment_intent' => $paymentIntentId,
        'user_id' => auth()->id(),
        'all_payments_with_this_intent' => Payment::where('payment_intent_id', $paymentIntentId)->pluck('id')->toArray(),
    ]);

    if (!$paymentIntentId) {
        return redirect()->route('client.dashboard')
            ->with('error', 'Invalid payment link.');
    }

    $payment = Payment::where('payment_intent_id', $paymentIntentId)->first();

    if (!$payment || $payment->client_id !== auth()->id()) {
        return redirect()->route('client.dashboard')
            ->with('error', 'Payment not found or access denied.');
    }

    if ($payment->status !== 'pending') {
        return redirect()->route('client.dashboard')
            ->with('success', 'Payment already processed! The freelancer is working on your project.');
    }

    // Your existing success logic (copy-paste from your old method)
    try {
        DB::transaction(function () use ($payment) {
            $this->paymentService->confirmPayment($payment, $payment->payment_intent_id);

            $gig = $payment->gig;
            $application = $gig->applications()
                ->where('freelancer_id', $payment->freelancer_id)
                ->firstOrFail();

            $application->update(['status' => 'accepted']);
            $gig->applications()
                ->where('id', '!=', $application->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);

            $gig->update(['status' => 'in_progress']);
        });

        return redirect()->route('client.dashboard')
            ->with('success', "Payment successful! Funds are in escrow. {$payment->freelancer->name} has been hired!");

    } catch (\Exception $e) {
        Log::error('Payment success failed', ['error' => $e->getMessage()]);
        return redirect()->route('client.dashboard')
            ->with('error', 'Payment succeeded but confirmation failed. Contact support.');
    }
}


    /**
     * Release payment from escrow to freelancer
     */
    public function release(Request $request, Payment $payment)
{
    $client = auth()->user();

    // Force load the gig – this will now work because of the relationship above
    $payment->load('gig');

    // Nuclear fallback – if still null, manually fetch it
    if (!$payment->gig && $payment->gig_id) {
        $payment->setRelation('gig', \App\Models\Gig::find($payment->gig_id));
    }

    \Log::info('RELEASE DEBUG', [
        'payment_id' => $payment->id,
        'gig_id' => $payment->gig_id,
        'gig_loaded' => $payment->gig ? 'YES' : 'NO',
        'gig_owner_id' => $payment->gig?->client_id,
        'client_id' => $client->id,
    ]);

    if (!$payment->gig || $payment->gig->client_id !== $client->id) {
        return response()->json([
            'success' => false,
            'error' => 'You do not have permission to release this payment.',
        ], 403);
    }

    if ($payment->status !== 'held') {
        return response()->json([
            'success' => false,
            'error' => 'Payment is not in escrow.',
        ], 400);
    }

    $result = $this->paymentService->releasePayment($payment);

    if ($result['success']) {
        return redirect()->route('client.dashboard')
            ->with('success', "Payment released! $".number_format($payment->freelancer_amount, 2)." has been sent to {$payment->freelancer->name}. The gig is now completed.");
    }

    return response()->json($result, 500);
}

    /**
     * Refund a payment
     */
    public function refund(Request $request, Payment $payment)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $client = auth()->user();

        // Verify client owns the gig
        if ($payment->gig->user_id !== $client->id) {
            return response()->json([
                'success' => false,
                'error' => 'You do not have permission to refund this payment.',
            ], 403);
        }

        try {
            $result = $this->paymentService->refundPayment(
                $payment,
                $request->reason
            );

            if ($result['success']) {
                // Update gig status
                $payment->gig->update([
                    'status' => 'cancelled',
                    'payment_completed' => false,
                ]);

                Log::info('Payment refunded', [
                    'payment_id' => $payment->id,
                    'amount' => $payment->amount,
                    'reason' => $request->reason,
                ]);

                // TODO: Send notifications

                return response()->json([
                    'success' => true,
                    'message' => 'Refund processed successfully. You will receive $' . number_format($payment->amount, 2) . ' back to your card in 5-10 business days.',
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 500);

        } catch (\Exception $e) {
            Log::error('Refund error: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
            ]);

            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing the refund.',
            ], 500);
        }
    }

    /**
     * Show payment details
     */
    public function show(Payment $payment)
    {
        $user = auth()->user();

        // Verify user is involved in this payment
        if ($payment->client_id !== $user->id && $payment->freelancer_id !== $user->id) {
            abort(403, 'Unauthorized access to payment details.');
        }

        return view('payments.show', [
            'payment' => $payment->load(['gig', 'client', 'freelancer', 'escrow', 'payout']),
        ]);
    }
}