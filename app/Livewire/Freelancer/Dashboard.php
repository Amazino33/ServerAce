<?php

namespace App\Livewire\Freelancer;

use App\Models\Gig;
use App\Models\GigApplication;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    // Properties for tab management
    public $activeTab = 'overview';

    // Properties for application modal
    public $showApplicationModal = false;
    public $selectedGig = null;
    public $coverLetter = '';
    public $proposedPrice = '';

    // Property for viewing application details
    public $showApplicationDetailModal = false;
    public $selectedApplication = null;

    /**
     * Switch between tabs
     */
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage(); // Reset pagination when switching tabs
    }

    /**
     * Open the application modal for a specific gig
     */
    public function openApplicationModal($gigId)
    {
        $this->selectedGig = Gig::findOrFail($gigId);
        
        // Pre-fill proposed price with gig's budget if available
        if ($this->selectedGig->budget_fixed) {
            $this->proposedPrice = $this->selectedGig->budget_fixed;
        } elseif ($this->selectedGig->budget_min) {
            $this->proposedPrice = $this->selectedGig->budget_min;
        }
        
        $this->showApplicationModal = true;
    }

    /**
     * Close the application modal
     */
    public function closeApplicationModal()
    {
        $this->showApplicationModal = false;
        $this->selectedGig = null;
        $this->coverLetter = '';
        $this->proposedPrice = '';
        $this->resetValidation();
    }

    /**
     * Submit application to a gig
     */
    public function submitApplication()
    {
        // Validate the input
        $this->validate([
            'coverLetter' => 'required|min:50|max:1000',
            'proposedPrice' => 'required|numeric|min:1',
        ], [
            'coverLetter.required' => 'Please write a cover letter',
            'coverLetter.min' => 'Cover letter must be at least 50 characters',
            'coverLetter.max' => 'Cover letter cannot exceed 1000 characters',
            'proposedPrice.required' => 'Please enter your proposed price',
            'proposedPrice.numeric' => 'Price must be a valid number',
            'proposedPrice.min' => 'Price must be at least $1',
        ]);

        // Check if user already applied
        if (auth()->user()->hasAppliedTo($this->selectedGig)) {
            session()->flash('error', 'You have already applied to this gig!');
            $this->closeApplicationModal();
            return;
        }

        // Create the application
        GigApplication::create([
            'gig_id' => $this->selectedGig->id,
            'freelancer_id' => auth()->id(),
            'cover_letter' => $this->coverLetter,
            'proposed_price' => $this->proposedPrice,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Application submitted successfully!');
        $this->closeApplicationModal();
    }

    /**
     * Open application detail modal
     */
    public function viewApplicationDetail($applicationId)
    {
        $this->selectedApplication = GigApplication::with(['gig', 'gig.client'])
            ->findOrFail($applicationId);
        $this->showApplicationDetailModal = true;
    }

    /**
     * Close application detail modal
     */
    public function closeApplicationDetailModal()
    {
        $this->showApplicationDetailModal = false;
        $this->selectedApplication = null;
    }

    /**
     * Withdraw/cancel a pending application
     */
    public function withdrawApplication($applicationId)
    {
        $application = GigApplication::where('freelancer_id', auth()->id())
            ->where('id', $applicationId)
            ->where('status', 'pending')
            ->firstOrFail();

        $application->delete();

        session()->flash('success', 'Application withdrawn successfully!');
        $this->closeApplicationDetailModal();
    }

    /**
     * Get statistics for the dashboard
     */
    public function getStatsProperty()
    {
        $userId = auth()->id();

        return [
            'total_applications' => GigApplication::where('freelancer_id', $userId)->count(),
            'pending_applications' => GigApplication::where('freelancer_id', $userId)->pending()->count(),
            'accepted_applications' => GigApplication::where('freelancer_id', $userId)->accepted()->count(),
            'rejected_applications' => GigApplication::where('freelancer_id', $userId)->rejected()->count(),
            'active_projects' => GigApplication::where('freelancer_id', $userId)->accepted()->count(),
        ];
    }

    /**
     * Get available gigs (not applied to, open, approved)
     */
    public function getAvailableGigsProperty()
    {
        $appliedGigIds = GigApplication::where('freelancer_id', auth()->id())
            ->pluck('gig_id');

        return Gig::with(['client', 'category'])
            ->live() // approved and open
            ->whereNotIn('id', $appliedGigIds)
            ->latest()
            ->paginate(10);
    }

    /**
     * Get user's applications with gig details
     */
    public function getMyApplicationsProperty()
    {
        return GigApplication::with(['gig', 'gig.client'])
            ->where('freelancer_id', auth()->id())
            ->latest()
            ->paginate(10);
    }

    /**
     * Get recent activity (last 5 applications)
     */
    public function getRecentActivityProperty()
    {
        return GigApplication::with(['gig'])
            ->where('freelancer_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.freelancer.dashboard', [
            'stats' => $this->stats,
            'availableGigs' => $this->availableGigs,
            'myApplications' => $this->myApplications,
            'recentActivity' => $this->recentActivity,
        ])->layout('layouts.app');
    }
}