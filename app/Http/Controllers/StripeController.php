<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Start Stripe Connect onboarding for freelancer
     */
    public function onboarding(Request $request)
    {
        $freelancer = auth()->user();

        // Verify user is a freelancer
        if ($freelancer->role->value !== 'freelancer') {
            return redirect()->back()
                ->with('error', 'Only freelancers can set up payment accounts.');
        }

        // Check if already onboarded
        if ($freelancer->stripe_onboarded) {
            return redirect()->route('freelancer.dashboard')
                ->with('info', 'You are already set up to receive payments.');
        }

        // Check if user already has a Stripe account but not onboarded
        if ($freelancer->stripe_account_id) {
            Log::info('Reusing existing Stripe account', [
                'user_id' => $freelancer->id,
                'stripe_account_id' => $freelancer->stripe_account_id,
            ]);
            
            // Generate new onboarding link for existing account
            return redirect()->route('freelancer.stripe.refresh');
        }

        try {
            // Create Stripe Connect account and get onboarding URL
            $result = $this->paymentService->createConnectAccount($freelancer);

            if ($result['success']) {
                // Refresh user to get updated stripe_account_id
                $freelancer->refresh();
                
                Log::info('Stripe onboarding started for freelancer', [
                    'user_id' => $freelancer->id,
                    'stripe_account_id' => $freelancer->stripe_account_id,
                ]);

                // Redirect to Stripe's onboarding page
                return redirect($result['onboarding_url']);
            }

            // Failed to create account
            Log::error('Failed to create Stripe Connect account', [
                'user_id' => $freelancer->id,
                'error' => $result['error'],
            ]);

            return redirect()->route('freelancer.dashboard')
                ->with('error', 'Failed to start payment setup. Please check your internet connection and try again. Error: ' . $result['error']);

        } catch (\Exception $e) {
            Log::error('Stripe onboarding error: ' . $e->getMessage(), [
                'user_id' => $freelancer->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('freelancer.dashboard')
                ->with('error', 'An error occurred while setting up payments. Please check your internet connection and try again.');
        }
    }

    /**
     * Handle successful return from Stripe onboarding
     */
    public function return(Request $request)
    {
        $freelancer = auth()->user();
        
        // Add this logging
        Log::info('Stripe return called', [
            'user_id' => $freelancer->id,
            'stripe_account_id' => $freelancer->stripe_account_id,
        ]);

        try {
            // Check if stripe_account_id exists
            if (!$freelancer->stripe_account_id) {
                Log::error('No stripe_account_id found for user on return', [
                    'user_id' => $freelancer->id,
                ]);
                
                return redirect()->route('freelancer.dashboard')
                    ->with('error', 'Payment setup incomplete. The Stripe account was not created. Please try again.');
            }

            $stripe = new \Stripe\StripeClient(config('cashier.secret'));

            // Retrieve the Stripe account to verify it's fully set up
            $account = $stripe->accounts->retrieve($freelancer->stripe_account_id);
            
            // Log account status
            Log::info('Stripe account retrieved', [
                'user_id' => $freelancer->id,
                'account_id' => $account->id,
                'charges_enabled' => $account->charges_enabled,
                'payouts_enabled' => $account->payouts_enabled,
                'details_submitted' => $account->details_submitted,
            ]);

            // Check if account is fully onboarded
            if ($account->charges_enabled && $account->payouts_enabled) {
                // Account is ready!
                $freelancer->update([
                    'stripe_onboarded' => true,
                    'stripe_onboarded_at' => now(),
                ]);

                Log::info('Freelancer completed Stripe onboarding', [
                    'user_id' => $freelancer->id,
                    'stripe_account_id' => $freelancer->stripe_account_id,
                ]);

                return redirect()->route('freelancer.dashboard')
                    ->with('success', '🎉 Great! Your payment account is set up. You can now receive payments from clients!');
            } else {
                // Account setup incomplete
                Log::warning('Stripe account not fully enabled', [
                    'user_id' => $freelancer->id,
                    'stripe_account_id' => $freelancer->stripe_account_id,
                    'charges_enabled' => $account->charges_enabled,
                    'payouts_enabled' => $account->payouts_enabled,
                ]);

                return redirect()->route('freelancer.dashboard')
                    ->with('warning', 'Your payment setup is incomplete. Please complete all required steps in the Stripe form to receive payments.');
            }

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe return error: ' . $e->getMessage(), [
                'user_id' => $freelancer->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('freelancer.dashboard')
                ->with('error', 'Unable to verify your payment setup. Please check your internet connection and try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Handle refresh/retry of Stripe onboarding
     */
    public function refresh(Request $request)
    {
        $freelancer = auth()->user();

        // Check if they have a Stripe account ID
        if (!$freelancer->stripe_account_id) {
            // No account yet, start fresh
            return redirect()->route('freelancer.stripe.onboarding');
        }

        try {
            $stripe = new \Stripe\StripeClient(config('cashier.secret'));

            // Generate a new onboarding link for existing account
            $accountLink = $stripe->accountLinks->create([
                'account' => $freelancer->stripe_account_id,
                'refresh_url' => route('freelancer.stripe.refresh'),
                'return_url' => route('freelancer.stripe.return'),
                'type' => 'account_onboarding',
            ]);

            Log::info('Stripe onboarding refreshed', [
                'user_id' => $freelancer->id,
                'stripe_account_id' => $freelancer->stripe_account_id,
            ]);

            // Redirect to Stripe's onboarding page again
            return redirect($accountLink->url);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe refresh error: ' . $e->getMessage(), [
                'user_id' => $freelancer->id,
            ]);

            return redirect()->route('freelancer.dashboard')
                ->with('error', 'Failed to restart payment setup. Please try again or contact support.');
        }
    }

    /**
     * Show Stripe account dashboard for freelancer
     */
    public function accountDashboard(Request $request)
    {
        $freelancer = auth()->user();

        // Check if onboarded
        if (!$freelancer->stripe_onboarded || !$freelancer->stripe_account_id) {
            return redirect()->route('freelancer.stripe.onboarding')
                ->with('info', 'Please complete payment setup first.');
        }

        try {
            $stripe = new \Stripe\StripeClient(config('cashier.secret'));

            // Create login link to Stripe Express Dashboard
            $loginLink = $stripe->accounts->createLoginLink($freelancer->stripe_account_id);

            Log::info('Freelancer accessing Stripe dashboard', [
                'user_id' => $freelancer->id,
            ]);

            // Redirect to Stripe Express Dashboard
            return redirect($loginLink->url);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe dashboard access error: ' . $e->getMessage(), [
                'user_id' => $freelancer->id,
            ]);

            return redirect()->route('freelancer.dashboard')
                ->with('error', 'Unable to access payment dashboard. Please contact support.');
        }
    }

    /**
     * Show Stripe account status and details
     */
    public function status(Request $request)
    {
        $freelancer = auth()->user();

        if (!$freelancer->stripe_account_id) {
            return redirect()->route('freelancer.dashboard')
                ->with('info', 'You have not set up payments yet.');
        }

        try {
            $stripe = new \Stripe\StripeClient(config('cashier.secret'));
            $account = $stripe->accounts->retrieve($freelancer->stripe_account_id);

            return view('freelancer.stripe.status', [
                'account' => $account,
                'freelancer' => $freelancer,
            ]);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe status check error: ' . $e->getMessage());

            return redirect()->route('freelancer.dashboard')
                ->with('error', 'Unable to retrieve payment account status.');
        }
    }

    /**
     * Remove Stripe account (disconnect)
     */
    public function disconnect(Request $request)
    {
        $freelancer = auth()->user();

        if (!$freelancer->stripe_account_id) {
            return redirect()->route('freelancer.dashboard')
                ->with('info', 'No payment account to disconnect.');
        }

        // Check if there are any pending payouts
        $hasPendingPayouts = $freelancer->payouts()
            ->whereIn('status', ['pending', 'processing'])
            ->exists();

        if ($hasPendingPayouts) {
            return redirect()->route('freelancer.dashboard')
                ->with('error', 'Cannot disconnect while you have pending payouts. Please wait for all payouts to complete.');
        }

        try {
            // Note: We don't delete the Stripe account, just disconnect it from our app
            $freelancer->update([
                'stripe_account_id' => null,
                'stripe_onboarded' => false,
                'stripe_onboarded_at' => null,
            ]);

            Log::info('Freelancer disconnected Stripe account', [
                'user_id' => $freelancer->id,
            ]);

            return redirect()->route('freelancer.dashboard')
                ->with('success', 'Payment account disconnected. You can reconnect anytime to receive payments again.');

        } catch (\Exception $e) {
            Log::error('Stripe disconnect error: ' . $e->getMessage());

            return redirect()->route('freelancer.dashboard')
                ->with('error', 'Failed to disconnect payment account. Please try again.');
        }
    }

    /**
     * Test endpoint to verify Stripe configuration (only for development)
     */
    public function test(Request $request)
    {
        // Only allow in local environment
        if (!app()->environment('local')) {
            abort(404);
        }

        try {
            $stripe = new \Stripe\StripeClient(config('cashier.secret'));
            
            // Test creating a payment intent
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => 1000, // $10.00
                'currency' => 'usd',
                'description' => 'Test payment',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Stripe is configured correctly!',
                'payment_intent_id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
                'config' => [
                    'has_publishable_key' => !empty(config('cashier.key')),
                    'has_secret_key' => !empty(config('cashier.secret')),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'config' => [
                    'has_publishable_key' => !empty(config('cashier.key')),
                    'has_secret_key' => !empty(config('cashier.secret')),
                ]
            ], 500);
        }
    }
}