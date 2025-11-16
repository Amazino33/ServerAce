<?php

namespace App\Livewire;

use App\Models\Gig;
use App\Models\GigApplication;
use App\Enums\GigStatus;
use Livewire\Component;

class ApplyToGig extends Component
{
    // ===========================
    // PROPERTIES
    // ===========================
    
    public $gigId; // Store the gig ID
    public Gig $gig; // The gig we're applying to
    public $modal = false; // Control modal visibility
    
    // Form fields
    public $cover_letter = '';
    public $proposed_price;
    
    // ===========================
    // VALIDATION RULES
    // ===========================
    
    protected $rules = [
        'cover_letter' => 'required|string|min:50|max:1000',
        'proposed_price' => 'required|numeric|min:5|max:1000000',
    ];

    // Custom validation messages
    protected $messages = [
        'cover_letter.required' => 'Please tell us why you\'re the best fit for this gig.',
        'cover_letter.min' => 'Your cover letter should be at least 50 characters.',
        'proposed_price.required' => 'Please enter your proposed price.',
        'proposed_price.min' => 'Minimum price is $5.',
    ];

    // ===========================
    // LIFECYCLE HOOKS
    // ===========================
    
    /**
     * Initialize component with the gig
     */
    public function mount($gig = null, $gigId = null)
    {
        // Handle both cases: passing model or ID
        if ($gig instanceof Gig) {
            $this->gig = $gig;
        } elseif ($gigId) {
            $this->gig = Gig::findOrFail($gigId);
        } elseif (is_numeric($gig)) {
            // If gig is passed as ID
            $this->gig = Gig::findOrFail($gig);
        } else {
            abort(404, 'Gig not found');
        }
        
        // Pre-fill proposed price with gig budget if available
        if ($this->gig->budget_fixed) {
            $this->proposed_price = $this->gig->budget_fixed;
        } elseif ($this->gig->budget_min && $this->gig->budget_max) {
            // Use middle of range as default
            $this->proposed_price = ($this->gig->budget_min + $this->gig->budget_max) / 2;
        }
    }

    // ===========================
    // ACTIONS
    // ===========================
    
    /**
     * Open the application modal
     */
    public function openModal()
    {
        // Check if user is logged in
        if (!auth()->check()) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Please login to apply for this gig.'
            ]);
            return redirect()->route('login');
        }

        // Check if user is trying to apply to their own gig
        if ($this->gig->client_id === auth()->id()) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'You cannot apply to your own gig!'
            ]);
            return;
        }

        // Check if gig is still open
        $gigStatus = is_object($this->gig->status) ? $this->gig->status->value : $this->gig->status;
        
        if ($gigStatus !== 'open') {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'This gig is no longer accepting applications.'
            ]);
            return;
        }

        // Check if user has already applied
        $hasApplied = GigApplication::where('gig_id', $this->gig->id)
            ->where('freelancer_id', auth()->id())
            ->exists();
            
        if ($hasApplied) {
            $this->dispatch('toast', [
                'type' => 'info',
                'message' => 'You have already applied to this gig.'
            ]);
            return;
        }

        // All checks passed - open modal
        $this->modal = true;
        $this->resetErrorBag();
    }

    /**
     * Close the modal
     */
    public function closeModal()
    {
        $this->modal = false;
        $this->reset(['cover_letter', 'proposed_price']);
    }

    /**
     * Submit the application
     */
    public function submit()
    {
        // Validate the form
        $this->validate();

        // Double-check all business rules (security!)
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if ($this->gig->client_id === auth()->id()) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'You cannot apply to your own gig!'
            ]);
            return;
        }

        // Check if already applied
        $hasApplied = GigApplication::where('gig_id', $this->gig->id)
            ->where('freelancer_id', auth()->id())
            ->exists();
            
        if ($hasApplied) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'You have already applied to this gig.'
            ]);
            return;
        }

        try {
            // Create the application
            GigApplication::create([
                'gig_id' => $this->gig->id,
                'freelancer_id' => auth()->id(),
                'cover_letter' => $this->cover_letter,
                'proposed_price' => $this->proposed_price,
                'status' => 'pending',
            ]);

            // TODO: Send notification to gig owner
            // $this->gig->client->notify(new NewGigApplication($application));

            // Show success message
            $this->dispatch('toast', [
                'type' => 'success',
                'title' => 'Application Submitted!',
                'message' => 'The client will review your application and get back to you.'
            ]);

            // Close modal and reset form
            $this->closeModal();

            // Refresh the component to update button state
            $this->dispatch('$refresh');

        } catch (\Exception $e) {
            // Handle any errors (like duplicate application caught by database)
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Something went wrong. Please try again.'
            ]);
            
            // Log the error for debugging
            logger()->error('Application submission failed', [
                'user_id' => auth()->id(),
                'gig_id' => $this->gig->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    // ===========================
    // COMPUTED PROPERTIES
    // ===========================
    
    /**
     * Check if current user can apply
     */
    public function getCanApplyProperty(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        if ($this->gig->client_id === auth()->id()) {
            return false;
        }

        // Check if already applied - use direct query instead of hasAppliedTo
        $hasApplied = GigApplication::where('gig_id', $this->gig->id)
            ->where('freelancer_id', auth()->id())
            ->exists();
            
        if ($hasApplied) {
            return false;
        }

        // Handle both string and enum status
        $gigStatus = is_object($this->gig->status) ? $this->gig->status->value : $this->gig->status;
        
        if ($gigStatus !== 'open') {
            return false;
        }

        return true;
    }

    /**
     * Get user's application if exists
     */
    public function getUserApplicationProperty()
    {
        if (!auth()->check()) {
            return null;
        }

        return GigApplication::where('gig_id', $this->gig->id)
            ->where('freelancer_id', auth()->id())
            ->first();
    }

    // ===========================
    // RENDER
    // ===========================
    
    public function render()
    {
        return view('livewire.apply-to-gig');
    }
}   