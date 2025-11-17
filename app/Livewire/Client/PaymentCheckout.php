<?php

namespace App\Livewire\Client;

use App\Models\Gig;
use App\Models\GigApplication;
use App\Services\PaymentService;
use Livewire\Component;

class PaymentCheckout extends Component
{
    public $gig;
    public $application;
    public $amount;
    public $showModal = false;
    public $clientSecret;
    public $paymentIntentId;
    public $processing = false;

    protected $listeners = ['openPaymentModal'];

    public function mount(Gig $gig = null, GigApplication $application = null)
    {
        if ($gig) {
            $this->gig = $gig;
        }
        
        if ($application) {
            $this->application = $application;
            $this->amount = (float) $application->proposed_price;
        }
    }

    public function openPaymentModal($gigId, $applicationId)
    {
        $this->gig = Gig::findOrFail($gigId);
        $this->application = GigApplication::findOrFail($applicationId);
    $this->amount = (float) $this->application->proposed_price;
        $this->showModal = true;
        
        $this->createPaymentIntent();
    }

    public function createPaymentIntent()
    {
        $paymentService = new PaymentService();
        
        $result = $paymentService->createPaymentIntent(
            $this->gig,
            auth()->user(),
            $this->application->freelancer,
            $this->amount
        );

        if ($result['success']) {
            $this->clientSecret = $result['client_secret'];
            $this->paymentIntentId = $result['payment_intent_id'];
        } else {
            session()->flash('error', $result['error']);
            $this->closeModal();
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['clientSecret', 'paymentIntentId', 'processing']);
    }

    public function render()
    {
        return view('livewire.client.payment-checkout');
    }
}