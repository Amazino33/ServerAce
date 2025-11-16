<?php

namespace App\Livewire\Admin;

use App\Models\Gig;
use App\Models\User;
use App\Enums\UserRole;
use Livewire\Component;
use Livewire\WithPagination;

class ManageInHouseAssignments extends Component
{
    use WithPagination;

    // ===========================
    // PROPERTIES
    // ===========================
    
    public $searchTerm = '';
    public $filterStatus = 'all'; // all, pending, assigned
    public $selectedGigId = null;
    public $showAssignmentModal = false;
    public $selectedDeveloperId = null;

    // ===========================
    // COMPUTED PROPERTIES
    // ===========================

    public function getInHouseRequestsProperty()
    {
        $query = Gig::where('assigned_to_inhouse', true)
            ->with(['client', 'inHouseDeveloper', 'category'])
            ->latest();

        // Filter by status
        if ($this->filterStatus === 'pending') {
            $query->whereNull('inhouse_developer_id');
        } elseif ($this->filterStatus === 'assigned') {
            $query->whereNotNull('inhouse_developer_id');
        }

        // Search by gig title or client name
        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->searchTerm . '%')
                  ->orWhereHas('client', function($clientQ) {
                      $clientQ->where('name', 'like', '%' . $this->searchTerm . '%');
                  });
            });
        }

        return $query->paginate(15);
    }

    public function getAvailableDevelopersProperty()
    {
        // Get users who might be in-house developers (could be admin or special role)
        // Adjust this based on your business logic
        return User::where('role', UserRole::ADMIN->value)
            ->orWhere(function($query) {
                // Or fetch from another table/field if you have specific in-house developers
                $query->where('email', 'like', '%@company.%'); // Example: company email domain
            })
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();
    }

    public function getStatsProperty()
    {
        return [
            'total_requests' => Gig::where('assigned_to_inhouse', true)->count(),
            'pending_requests' => Gig::where('assigned_to_inhouse', true)
                ->whereNull('inhouse_developer_id')
                ->count(),
            'assigned_requests' => Gig::where('assigned_to_inhouse', true)
                ->whereNotNull('inhouse_developer_id')
                ->count(),
        ];
    }

    // ===========================
    // ACTIONS
    // ===========================

    public function viewGigDetails($gigId)
    {
        $this->selectedGigId = $gigId;
        $this->showAssignmentModal = true;
    }

    public function closeAssignmentModal()
    {
        $this->showAssignmentModal = false;
        $this->selectedGigId = null;
        $this->selectedDeveloperId = null;
    }

    public function assignDeveloper()
    {
        if (!$this->selectedGigId || !$this->selectedDeveloperId) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Please select a developer to assign.'
            ]);
            return;
        }

        $gig = Gig::findOrFail($this->selectedGigId);
        $developer = User::findOrFail($this->selectedDeveloperId);

        // Update gig with developer assignment
        $gig->update([
            'inhouse_developer_id' => $this->selectedDeveloperId,
            'inhouse_assigned_at' => now(),
            'status' => 'in_progress',
        ]);

        // Reject all pending applications for this gig
        $gig->applications()
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        $this->dispatch('toast', [
            'type' => 'success',
            'title' => 'Developer Assigned!',
            'message' => "The gig '{$gig->title}' has been assigned to {$developer->name}."
        ]);

        $this->closeAssignmentModal();
    }

    public function removeAssignment($gigId)
    {
        $gig = Gig::findOrFail($gigId);
        $gig->removeInHouseAssignment();

        $this->dispatch('toast', [
            'type' => 'info',
            'message' => 'In-house assignment removed.'
        ]);
    }

    public function cancelInHouseRequest($gigId)
    {
        $gig = Gig::findOrFail($gigId);
        $gig->removeInHouseAssignment();

        $this->dispatch('toast', [
            'type' => 'info',
            'message' => 'In-house request cancelled.'
        ]);
    }

    // ===========================
    // RENDER
    // ===========================

    public function render()
    {
        return view('livewire.admin.manage-inhouse-assignments', [
            'requests' => $this->inHouseRequests,
            'stats' => $this->stats,
            'developers' => $this->availableDevelopers,
            'selectedGig' => $this->selectedGigId 
                ? Gig::with(['client', 'inHouseDeveloper', 'category'])->find($this->selectedGigId)
                : null,
        ])->layout('layouts.app');
    }
}
