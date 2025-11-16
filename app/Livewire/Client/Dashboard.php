<?php

namespace App\Livewire\Client;

use App\Models\Gig;
use App\Models\GigApplication;
use App\Enums\GigStatus;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    use WithPagination;

    // ===========================
    // PROPERTIES
    // ===========================
    
    public $activeTab = 'overview'; // overview, gigs, applications
    public $selectedGigId = null;
    public $selectedApplicationId = null;
    public $showApplicationModal = false;
    public $inhouseNotes = '';
    
    // Filters
    public $filterStatus = 'all'; // all, open, closed, in_progress
    public $filterGigForApplications = 'all';
    public $searchTerm = '';

    // ===========================
    // LIFECYCLE HOOKS
    // ===========================
    
    public function mount()
    {
        // Initialize recent data when component loads
        $this->loadRecentData();
    }

    // ===========================
    // HELPER METHODS
    // ===========================
    
    public function loadRecentData()
    {
        // Load recent gigs (last 5)
        $this->recentGigs = Gig::where('client_id', auth()->id())
            ->withCount(['applications', 'pendingApplications'])
            ->latest()
            ->take(5)
            ->get();

        // Load recent applications (last 5)
        $this->recentApplications = GigApplication::whereHas('gig', function($query) {
                $query->where('client_id', auth()->id());
            })
            ->with(['freelancer', 'gig'])
            ->latest()
            ->take(5)
            ->get();
    }

    // ===========================
    // COMPUTED PROPERTIES - Dashboard Stats
    // ===========================
    
    public function getInProgressGigsProperty()
    {
        return Gig::where('client_id', auth()->id())
            ->where(function($query) {
                $query->where('status', GigStatus::IN_PROGRESS->value)
                      ->orWhere('status', GigStatus::IN_PROGRESS);
            })
            ->with([
                'inHouseDeveloper',
                'applications' => function($query) {
                    $query->where('status', 'accepted')->with('freelancer');
                },
                'category'
            ])
            ->latest('updated_at')
            ->paginate(10);
    }
    public function getStatsProperty()
    {
        $userId = auth()->id();
        
        return [
            'total_gigs' => Gig::where('client_id', $userId)->count(),
            'active_gigs' => Gig::where('client_id', $userId)
                ->where(function($query) {
                    $query->where('status', GigStatus::OPEN->value)
                          ->orWhere('status', GigStatus::OPEN);
                })
                ->count(),
            'total_applications' => GigApplication::whereHas('gig', function($query) use ($userId) {
                $query->where('client_id', $userId);
            })->count(),
            
            'in_progress_gigs' => Gig::where('client_id', $userId)
                ->where(function($query) {
                    $query->where('status', GigStatus::IN_PROGRESS->value)
                          ->orWhere('status', GigStatus::IN_PROGRESS);
                })
                ->count(), 
            'pending_applications' => GigApplication::whereHas('gig', function($query) use ($userId) {
                $query->where('client_id', $userId);
            })->where('status', 'pending')->count(),
            'accepted_applications' => GigApplication::whereHas('gig', function($query) use ($userId) {
                $query->where('client_id', $userId);
            })->where('status', 'accepted')->count(),
        ];
    }

    public function getMyGigsProperty()
    {
        $query = Gig::where('client_id', auth()->id())
            ->withCount(['applications', 'pendingApplications'])
            ->with(['category', 'media']);

        // Apply status filter
        if ($this->filterStatus !== 'all') {
            $query->where(function($q) {
                $q->where('status', $this->filterStatus)
                  ->orWhere('status', GigStatus::from($this->filterStatus));
            });
        }

        // Apply search
        if ($this->searchTerm) {
            $query->where('title', 'like', '%' . $this->searchTerm . '%');
        }

        return $query->latest()->paginate(10);
    }

    public function getAllApplicationsProperty()
    {
        $query = GigApplication::whereHas('gig', function($q) {
                $q->where('client_id', auth()->id());
            })
            ->with(['freelancer', 'gig']);

        // Filter by gig
        if ($this->filterGigForApplications !== 'all') {
            $query->where('gig_id', $this->filterGigForApplications);
        }

        return $query->latest()->paginate(15);
    }

    public function getMyGigsForFilterProperty()
    {
        return Gig::where('client_id', auth()->id())
            ->select('id', 'title')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getRecentGigsProperty()
    {
        return Gig::where('client_id', auth()->id())
            ->withCount(['applications', 'pendingApplications'])
            ->latest()
            ->take(5)
            ->get();
    }

    public function getRecentApplicationsProperty()
    {
        return GigApplication::whereHas('gig', function($query) {
                $query->where('client_id', auth()->id());
            })
            ->with(['freelancer', 'gig'])
            ->latest()
            ->take(5)
            ->get();
    }

    public function viewGigDetails($gigId)
    {
        return redirect()->route('gigs.show', $gigId);
    }

    public function viewFreelancerProfile($freelancerId)
    {
        // Implement based on your routes
        return redirect()->route('gigs.index', $freelancerId);
    }

    public function markAsComplete($gigId)
    {
        $gig = Gig::where('id', $gigId)
            ->where('client_id', auth()->id())
            ->firstOrFail();

        $gig->update([
            'status' => GigStatus::COMPLETED,
            'completed_at' => now(),
        ]);

        session()->flash('success', 'Gig marked as complete! You can now leave a review.');
        
        // TODO: Trigger payment release if you have escrow
        // TODO: Send notification to freelancer/developer
    }

    public function openDisputeModal($gigId)
    {
        // Implement dispute functionality
        session()->flash('info', 'Dispute feature coming soon!');
    }




    // ===========================
    // ACTIONS - Tab Navigation
    // ===========================
    
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // ===========================
    // ACTIONS - Gig Management
    // ===========================
    
    public function toggleGigStatus($gigId)
    {
        $gig = Gig::where('id', $gigId)
            ->where('client_id', auth()->id())
            ->firstOrFail();

        $currentStatus = is_object($gig->status) ? $gig->status->value : $gig->status;
        
        if ($currentStatus === 'open') {
            $gig->update(['status' => GigStatus::CLOSED->value]);
            session()->flash('success', 'Gig closed successfully.');
        } else {
            $gig->update(['status' => GigStatus::OPEN->value]);
            session()->flash('success', 'Gig reopened successfully.');
        }
    }

    public function deleteGig($gigId)
    {
        $gig = Gig::where('id', $gigId)
            ->where('client_id', auth()->id())
            ->firstOrFail();

        // Check if gig has accepted applications
        if ($gig->applications()->where('status', 'accepted')->exists()) {
            session()->flash('error', 'Cannot delete gig with accepted applications.');
            return;
        }

        $gig->delete();

        session()->flash('success', 'Gig deleted successfully.');
    }

    // ===========================
    // ACTIONS - Application Management
    // ===========================
    
    public function viewApplication($applicationId)
    {
        $this->selectedApplicationId = $applicationId;
        $this->showApplicationModal = true;
    }

    public function closeApplicationModal()
    {
        $this->showApplicationModal = false;
        $this->selectedApplicationId = null;
    }

    public function acceptApplication($applicationId)
    {
        $application = GigApplication::where('id', $applicationId)
            ->whereHas('gig', function($q) {
                $q->where('client_id', auth()->id());
            })
            ->firstOrFail();

        // Reject all other applications for this gig
        GigApplication::where('gig_id', $application->gig_id)
            ->where('id', '!=', $applicationId)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        // Accept this application
        $application->update(['status' => 'accepted']);

        // Update gig status to in_progress
        $application->gig->update(['status' => GigStatus::IN_PROGRESS->value]);

        session()->flash('success', 'Application accepted! The freelancer has been notified.');

        $this->closeApplicationModal();
        
        // TODO: Send notification to freelancer
        // $application->freelancer->notify(new ApplicationAccepted($application));
    }

    public function rejectApplication($applicationId)
    {
        $application = GigApplication::where('id', $applicationId)
            ->whereHas('gig', function($q) {
                $q->where('client_id', auth()->id());
            })
            ->firstOrFail();

        $application->update(['status' => 'rejected']);

        session()->flash('success', 'Application rejected.');

        $this->closeApplicationModal();
        
        // TODO: Send notification to freelancer
        // $application->freelancer->notify(new ApplicationRejected($application));
    }

    /**
     * Assign a gig to an in-house developer
     * Request will be sent to site admins for handling
     */
    public function assignToInHouseDeveloper()
    {
        if (!$this->selectedGigId) {
            session()->flash('error', 'No gig selected.');
            return;
        }

        $gig = Gig::where('id', $this->selectedGigId)
            ->where('client_id', auth()->id())
            ->firstOrFail();

        // Assign to in-house (status will be handled by admin)
        $gig->assignToInHouseDeveloper(null, $this->inhouseNotes);

        // Reject all pending applications
        GigApplication::where('gig_id', $this->selectedGigId)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        session()->flash('success', 'Request submitted! Your request to assign this gig to an in-house developer has been submitted. Our team will review and assign a developer soon.');

        // Reset modal state
        $this->inhouseNotes = '';
        $this->selectedGigId = null;
    }

    /**
     * Open the in-house request modal (called from UI)
     */
    public function openInHouseModal($gigId)
    {
        $this->selectedGigId = $gigId;
        $this->inhouseNotes = '';
    }

    // ===========================
    // RENDER
    // ===========================
    
    public function render()
    {
        return view('livewire.client.dashboard', [
            'stats' => $this->stats,
            'gigs' => $this->activeTab === 'gigs' ? $this->myGigs : collect(),
            'applications' => $this->activeTab === 'applications' ? $this->allApplications : collect(),
            'inProgressGigs' => $this->activeTab === 'in_progress' ? $this->inProgressGigs : collect(), // ADD THIS
            'gigsForFilter' => $this->myGigsForFilter,
            'recentGigs' => $this->recentGigs,
            'recentApplications' => $this->recentApplications,
            'selectedApplication' => $this->selectedApplicationId 
                ? GigApplication::with(['freelancer', 'gig'])->find($this->selectedApplicationId) 
                : null,
        ])->layout('layouts.app');
    }
}