<?php

namespace App\Services;

use App\Models\Gig;
use App\Models\Payment;
use App\Models\EscrowAccount;
use App\Models\Payout;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

class PaymentService
{
    private StripeClient $stripe;
    private float $platformFeePercentage = 10.0; // 10% platform fee

    public function __construct()
    {
        $this->stripe = new StripeClient(config('cashier.secret'));
    }

    /**
     * Create a payment intent for a gig
     */
    public function createPaymentIntent(Gig $gig, User $client, User $freelancer, float $amount): array
    {
        try {
            $platformFee = $this->calculatePlatformFee($amount);
            $freelancerAmount = $amount - $platformFee;

            // Create Stripe Payment Intent
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $amount * 100, // Convert to cents
                'currency' => 'usd',
                'customer' => $client->stripe_id ?? $this->createStripeCustomer($client),
                'description' => "Payment for gig: {$gig->title}",
                'metadata' => [
                    'gig_id' => $gig->id,
                    'client_id' => $client->id,
                    'freelancer_id' => $freelancer->id,
                ],
                'capture_method' => 'automatic', // Charge immediately
            ]);

            // Create payment record
            $payment = Payment::create([
                'gig_id' => $gig->id,
                'client_id' => $client->id,
                'freelancer_id' => $freelancer->id,
                'amount' => $amount,
                'platform_fee' => $platformFee,
                'freelancer_amount' => $freelancerAmount,
                'currency' => 'USD',
                'status' => 'pending',
                'payment_intent_id' => $paymentIntent->id,
                'description' => "Payment for: {$gig->title}",
            ]);

            // Create transaction record for client
            Transaction::create([
                'user_id' => $client->id,
                'transactionable_type' => Payment::class,
                'transactionable_id' => $payment->id,
                'type' => 'payment',
                'amount' => -$amount, // Negative for client (outgoing)
                'currency' => 'USD',
                'status' => 'pending',
                'description' => "Payment for gig: {$gig->title}",
            ]);

            return [
                'success' => true,
                'payment' => $payment,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ];

        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Confirm payment and move to escrow
     */
    public function confirmPayment(Payment $payment, string $paymentIntentId): bool
    {
        try {
            // Retrieve payment intent from Stripe
            $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                DB::transaction(function () use ($payment, $paymentIntent) {
                    // Update payment status
                    $payment->update([
                        'status' => 'held',
                        'stripe_charge_id' => $paymentIntent->charges->data[0]->id ?? null,
                        'paid_at' => now(),
                    ]);

                    // Create escrow account
                    EscrowAccount::create([
                        'payment_id' => $payment->id,
                        'gig_id' => $payment->gig_id,
                        'amount' => $payment->freelancer_amount,
                        'status' => 'holding',
                        'held_at' => now(),
                    ]);

                    // Update gig payment status
                    $payment->gig->update([
                        'payment_completed' => true,
                        'payment_completed_at' => now(),
                        'payment_id' => $payment->id,
                    ]);

                    // Update transaction status
                    Transaction::where('transactionable_id', $payment->id)
                        ->where('transactionable_type', Payment::class)
                        ->update(['status' => 'completed']);
                });

                return true;
            }

            return false;

        } catch (ApiErrorException $e) {
            \Log::error('Payment confirmation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Release payment from escrow to freelancer
     */
    public function releasePayment(Payment $payment, string $releaseNotes = null): array
    {
        try {
            $freelancer = $payment->freelancer;

            // Ensure freelancer has Stripe Connect account
            if (!$freelancer->stripe_account_id) {
                return [
                    'success' => false,
                    'error' => 'Freelancer has not set up payout account.',
                ];
            }

            DB::transaction(function () use ($payment, $freelancer, $releaseNotes) {
                // Create transfer to freelancer
                $transfer = $this->stripe->transfers->create([
                    'amount' => $payment->freelancer_amount * 100, // Convert to cents
                    'currency' => 'usd',
                    'destination' => $freelancer->stripe_account_id,
                    'description' => "Payout for gig: {$payment->gig->title}",
                    'metadata' => [
                        'payment_id' => $payment->id,
                        'gig_id' => $payment->gig_id,
                    ],
                ]);

                // Update payment status
                $payment->release();

                // Release escrow
                $payment->escrow->release($releaseNotes);

                // Create payout record
                $payout = Payout::create([
                    'freelancer_id' => $freelancer->id,
                    'payment_id' => $payment->id,
                    'amount' => $payment->freelancer_amount,
                    'currency' => 'USD',
                    'status' => 'paid',
                    'stripe_transfer_id' => $transfer->id,
                    'paid_at' => now(),
                ]);

                // Create transaction for freelancer (incoming)
                Transaction::create([
                    'user_id' => $freelancer->id,
                    'transactionable_type' => Payout::class,
                    'transactionable_id' => $payout->id,
                    'type' => 'payout',
                    'amount' => $payment->freelancer_amount, // Positive for freelancer
                    'currency' => 'USD',
                    'status' => 'completed',
                    'description' => "Payout for gig: {$payment->gig->title}",
                ]);

                // Create platform fee transaction
                Transaction::create([
                    'user_id' => $payment->client_id,
                    'transactionable_type' => Payment::class,
                    'transactionable_id' => $payment->id,
                    'type' => 'fee',
                    'amount' => -$payment->platform_fee,
                    'currency' => 'USD',
                    'status' => 'completed',
                    'description' => "Platform fee for gig: {$payment->gig->title}",
                ]);
            });

            return [
                'success' => true,
                'message' => 'Payment released successfully to freelancer.',
            ];

        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Refund a payment
     */
    public function refundPayment(Payment $payment, string $reason = null): array
    {
        try {
            if (!$payment->canBeRefunded()) {
                return [
                    'success' => false,
                    'error' => 'Payment cannot be refunded.',
                ];
            }

            // Create Stripe refund
            $refund = $this->stripe->refunds->create([
                'charge' => $payment->stripe_charge_id,
                'reason' => 'requested_by_customer',
                'metadata' => [
                    'payment_id' => $payment->id,
                    'reason' => $reason,
                ],
            ]);

            DB::transaction(function () use ($payment, $refund, $reason) {
                // Update payment status
                $payment->update([
                    'status' => 'refunded',
                    'refunded_at' => now(),
                ]);

                // Update escrow if exists
                if ($payment->escrow) {
                    $payment->escrow->update([
                        'status' => 'refunded',
                        'release_notes' => $reason,
                    ]);
                }

                // Create refund transaction
                Transaction::create([
                    'user_id' => $payment->client_id,
                    'transactionable_type' => Payment::class,
                    'transactionable_id' => $payment->id,
                    'type' => 'refund',
                    'amount' => $payment->amount, // Positive (money back)
                    'currency' => 'USD',
                    'status' => 'completed',
                    'description' => "Refund for gig: {$payment->gig->title}",
                    'metadata' => ['reason' => $reason],
                ]);
            });

            return [
                'success' => true,
                'message' => 'Payment refunded successfully.',
                'refund_id' => $refund->id,
            ];

        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Calculate platform fee
     */
    private function calculatePlatformFee(float $amount): float
    {
        return round($amount * ($this->platformFeePercentage / 100), 2);
    }

    /**
     * Create Stripe customer for user
     */
    private function createStripeCustomer(User $user): string
    {
        $customer = $this->stripe->customers->create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

        $user->update(['stripe_id' => $customer->id]);

        return $customer->id;
    }

    /**
     * Create Stripe Connect account for freelancer
     */
    public function createConnectAccount(User $freelancer)
    {
        try {
            // Log the start
            \Log::info('Starting Stripe Connect account creation', [
                'user_id' => $freelancer->id,
                'email' => $freelancer->email,
            ]);

            $account = $this->stripe->accounts->create([
                'type' => 'express',
                'country' => 'US',
                'email' => $freelancer->email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
                'metadata' => [
                    'user_id' => $freelancer->id,
                ],
            ]);

            \Log::info('Stripe account created', [
                'user_id' => $freelancer->id,
                'account_id' => $account->id,
            ]);

            // Save to database with error checking
            $updated = $freelancer->update([
                'stripe_account_id' => $account->id,
            ]);

            if (!$updated) {
                \Log::error('Failed to save stripe_account_id to database', [
                    'user_id' => $freelancer->id,
                    'account_id' => $account->id,
                ]);
                
                return [
                    'success' => false,
                    'error' => 'Failed to save account to database',
                ];
            }

            \Log::info('Stripe account ID saved to database', [
                'user_id' => $freelancer->id,
                'stripe_account_id' => $freelancer->fresh()->stripe_account_id,
            ]);

            // Create account link for onboarding
            $accountLink = $this->stripe->accountLinks->create([
                'account' => $account->id,
                'refresh_url' => route('freelancer.stripe.refresh'),
                'return_url' => route('freelancer.stripe.return'),
                'type' => 'account_onboarding',
            ]);

            \Log::info('Account link created', [
                'user_id' => $freelancer->id,
                'url' => $accountLink->url,
            ]);

            return [
                'success' => true,
                'onboarding_url' => $accountLink->url,
                'account_id' => $account->id,
            ];

        } catch (ApiErrorException $e) {
            \Log::error('Stripe API error in createConnectAccount', [
                'user_id' => $freelancer->id,
                'error' => $e->getMessage(),
                'code' => $e->getStripeCode(),
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        } catch (\Exception $e) {
            \Log::error('Unexpected error in createConnectAccount', [
                'user_id' => $freelancer->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'error' => 'Unexpected error: ' . $e->getMessage(),
            ];
        }
    }
}