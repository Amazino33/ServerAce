<?php

namespace App\Livewire\Client;

use App\Models\Gig;
use App\Models\GigApplication;
use App\Models\Payment;
use App\Services\PaymentService;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class PaymentCheckout extends Component
{
    // Modal state
    public $isOpen = false;
    
    // Payment data
    public $gig;
    public $application;
    public $paymentDetails = null;
    public $clientSecret = null;
    public $paymentId = null;
    public $error = null;

    // Listen for the event from dashboard
    protected $listeners = ['openPaymentModal'];

    /**
     * Open payment modal and create payment intent
     */
    public function openPaymentModal($gigId, $applicationId)
    {
        // Add debug logging
        Log::info('PaymentCheckout: openPaymentModal called', [
            'gigId' => $gigId,
            'applicationId' => $applicationId,
            'userId' => auth()->id()
        ]);

        // Also log to browser console
        $this->js('console.log("Payment modal opening...", ' . json_encode([
            'gigId' => $gigId,
            'applicationId' => $applicationId
        ]) . ')');

        try {
            // Load gig and application
            $this->gig = Gig::findOrFail($gigId);
            $this->application = GigApplication::with('freelancer')->findOrFail($applicationId);
            
            Log::info('PaymentCheckout: Models loaded', [
                'gig_id' => $this->gig->id,
                'application_id' => $this->application->id,
                'freelancer_name' => $this->application->freelancer->name
            ]);
            
            // Verify this is the client's gig
            if ($this->gig->client_id !== auth()->id()) {
                $this->error = 'Unauthorized action.';
                Log::error('PaymentCheckout: Unauthorized access');
                return;
            }
            
            // Verify freelancer is onboarded
            if (!$this->application->freelancer->stripe_onboarded) {
                $this->error = 'This freelancer has not set up their payment account yet. They cannot receive payments.';
                Log::error('PaymentCheckout: Freelancer not onboarded');
                return;
            }
            
            // Calculate payment details
            $amount = $this->application->proposed_price;
            $platformFee = round($amount * 0.10, 2); // 10% fee
            $freelancerAmount = $amount - $platformFee;
            
            $this->paymentDetails = [
                'amount' => $amount,
                'platform_fee' => $platformFee,
                'freelancer_amount' => $freelancerAmount,
            ];
            
            Log::info('PaymentCheckout: Payment details calculated', $this->paymentDetails);
            
            // Create payment intent
            $this->createPaymentIntent();
            
            // Open modal if no errors
            if (!$this->error) {
                $this->isOpen = true;
                Log::info('PaymentCheckout: Modal opened successfully');
                
                // Dispatch browser event to confirm modal is open
                $this->js('console.log("Payment modal is now OPEN")');
            } else {
                Log::error('PaymentCheckout: Error preventing modal open', ['error' => $this->error]);
            }
            
        } catch (\Exception $e) {
            Log::error('PaymentCheckout: Exception in openPaymentModal', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->error = 'Failed to open payment modal. Please try again.';
        }
    }

    /**
     * Create Stripe payment intent
     */
    private function createPaymentIntent()
    {
        try {
            $paymentService = new PaymentService();
            
            $result = $paymentService->createPaymentIntent(
                $this->gig,
                auth()->user(),
                $this->application->freelancer,
                $this->paymentDetails['amount']
            );

            if ($result['success']) {
                $this->clientSecret = $result['client_secret'];
                $this->paymentId = $result['payment']->id;
                
                Log::info('Payment intent created', [
                    'payment_id' => $this->paymentId,
                    'client_secret' => substr($this->clientSecret, 0, 20) . '...',
                ]);
            } else {
                $this->error = $result['error'] ?? 'Failed to create payment intent.';
                
                Log::error('Payment intent creation failed', [
                    'error' => $this->error,
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Payment intent error: ' . $e->getMessage());
            $this->error = 'Payment system error. Please try again or contact support.';
        }
    }

    /**
     * Close the modal
     */
    public function close()
    {
        Log::info('PaymentCheckout: Modal closing');
        $this->isOpen = false;
        $this->reset(['gig', 'application', 'paymentDetails', 'clientSecret', 'paymentId', 'error']);
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.client.payment-checkout');
    }
}