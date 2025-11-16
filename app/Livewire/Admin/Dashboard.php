<?php

namespace App\Livewire\Admin;

use App\Models\Gig;
use App\Models\User;
use App\Models\GigApplication;
use App\Enums\UserRole;
use App\Enums\GigStatus;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    // Properties for tab management
    public $activeTab = 'overview';

    // Properties for modals
    public $showGigDetailModal = false;
    public $selectedGig = null;

    public $showUserDetailModal = false;
    public $selectedUser = null;

    // Filters
    public $gigStatusFilter = 'all'; // all, pending, approved, open, closed
    public $userRoleFilter = 'all'; // all, client, freelancer

    /**
     * Switch between tabs
     */
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    /**
     * Set gig status filter
     */
    public function setGigStatusFilter($status)
    {
        $this->gigStatusFilter = $status;
        $this->resetPage();
    }

    /**
     * Set user role filter
     */
    public function setUserRoleFilter($role)
    {
        $this->userRoleFilter = $role;
        $this->resetPage();
    }

    /**
     * View gig details
     */
    public function viewGigDetail($gigId)
    {
        $this->selectedGig = Gig::with(['client', 'category', 'applications', 'freelancer'])
            ->findOrFail($gigId);
        $this->showGigDetailModal = true;
    }

    /**
     * Close gig detail modal
     */
    public function closeGigDetailModal()
    {
        $this->showGigDetailModal = false;
        $this->selectedGig = null;
    }

    /**
     * View user details
     */
    public function viewUserDetail($userId)
    {
        $this->selectedUser = User::with(['postedGigs', 'gigApplications'])
            ->findOrFail($userId);
        $this->showUserDetailModal = true;
    }

    /**
     * Close user detail modal
     */
    public function closeUserDetailModal()
    {
        $this->showUserDetailModal = false;
        $this->selectedUser = null;
    }

    /**
     * Approve a gig
     */
    public function approveGig($gigId)
    {
        $gig = Gig::findOrFail($gigId);
        $gig->update([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        session()->flash('success', 'Gig approved successfully!');
        $this->closeGigDetailModal();
    }

    /**
     * Reject a gig (unapprove it)
     */
    public function rejectGig($gigId)
    {
        $gig = Gig::findOrFail($gigId);
        $gig->update([
            'is_approved' => false,
            'approved_at' => null,
            'approved_by' => null,
        ]);

        session()->flash('success', 'Gig rejected successfully!');
        $this->closeGigDetailModal();
    }

    /**
     * Delete a gig
     */
    public function deleteGig($gigId)
    {
        $gig = Gig::findOrFail($gigId);
        
        // Delete all applications first
        $gig->applications()->delete();
        
        // Delete the gig
        $gig->delete();

        session()->flash('success', 'Gig deleted successfully!');
        $this->closeGigDetailModal();
    }

    /**
     * Ban/suspend a user
     */
    public function toggleUserStatus($userId)
    {
        $user = User::findOrFail($userId);
        
        // You might want to add a 'is_banned' column to users table
        // For now, we'll just show a success message
        session()->flash('success', 'User status updated!');
        $this->closeUserDetailModal();
    }

    /**
     * Delete a user
     */
    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        
        // Delete user's gigs and applications
        $user->postedGigs()->delete();
        $user->gigApplications()->delete();
        
        // Delete the user
        $user->delete();

        session()->flash('success', 'User deleted successfully!');
        $this->closeUserDetailModal();
    }

    /**
     * Get dashboard statistics
     */
    public function getStatsProperty()
    {
        return [
            // User stats
            'total_users' => User::where('role', '!=', UserRole::ADMIN)->count(),
            'total_clients' => User::where('role', UserRole::CLIENT)->count(),
            'total_freelancers' => User::where('role', UserRole::FREELANCER)->count(),
            
            // Gig stats
            'total_gigs' => Gig::count(),
            'pending_gigs' => Gig::where('is_approved', false)->count(),
            'approved_gigs' => Gig::where('is_approved', true)->count(),
            'open_gigs' => Gig::open()->count(),
            'completed_gigs' => Gig::where('status', GigStatus::COMPLETED)->count(),
            
            // Application stats
            'total_applications' => GigApplication::count(),
            'pending_applications' => GigApplication::pending()->count(),
            'accepted_applications' => GigApplication::accepted()->count(),
        ];
    }

    /**
     * Get filtered gigs
     */
    public function getGigsProperty()
    {
        $query = Gig::with(['client', 'category', 'applications'])
            ->latest();

        // Apply filters
        if ($this->gigStatusFilter === 'pending') {
            $query->where('is_approved', false);
        } elseif ($this->gigStatusFilter === 'approved') {
            $query->where('is_approved', true);
        } elseif ($this->gigStatusFilter === 'open') {
            $query->open();
        } elseif ($this->gigStatusFilter === 'closed') {
            $query->where('status', GigStatus::COMPLETED);
        }

        return $query->paginate(15);
    }

    /**
     * Get filtered users
     */
    public function getUsersProperty()
    {
        $query = User::where('role', '!=', UserRole::ADMIN)
            ->withCount(['postedGigs', 'gigApplications'])
            ->latest();

        // Apply filters
        if ($this->userRoleFilter === 'client') {
            $query->where('role', UserRole::CLIENT);
        } elseif ($this->userRoleFilter === 'freelancer') {
            $query->where('role', UserRole::FREELANCER);
        }

        return $query->paginate(15);
    }

    /**
     * Get all applications for monitoring
     */
    public function getApplicationsProperty()
    {
        return GigApplication::with(['gig', 'freelancer', 'gig.client'])
            ->latest()
            ->paginate(15);
    }

    /**
     * Get recent activity
     */
    public function getRecentActivityProperty()
    {
        // Combine recent gigs, users, and applications
        $recentGigs = Gig::with('client')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($gig) {
                return [
                    'type' => 'gig',
                    'data' => $gig,
                    'created_at' => $gig->created_at,
                ];
            });

        $recentUsers = User::where('role', '!=', UserRole::ADMIN)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user',
                    'data' => $user,
                    'created_at' => $user->created_at,
                ];
            });

        $recentApplications = GigApplication::with(['gig', 'freelancer'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($app) {
                return [
                    'type' => 'application',
                    'data' => $app,
                    'created_at' => $app->created_at,
                ];
            });

        // Merge and sort by date
        return collect()
            ->merge($recentGigs)
            ->merge($recentUsers)
            ->merge($recentApplications)
            ->sortByDesc('created_at')
            ->take(10);
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'stats' => $this->stats,
            'gigs' => $this->gigs,
            'users' => $this->users,
            'applications' => $this->applications,
            'recentActivity' => $this->recentActivity,
        ])->layout('layouts.app');
    }
}