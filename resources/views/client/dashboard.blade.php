<div>
    <!-- Debug Info - Remove this after testing -->
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
        <p><strong>Debug:</strong> selectedGigId = {{ $selectedGigId ?? 'null' }}</p>
        <p><strong>Debug:</strong> Modal should show = {{ $selectedGigId ? 'YES' : 'NO' }}</p>
    </div>

    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">Client Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Tab Navigation -->
            <div class="mb-6 border-b border-gray-200">
                <nav class="flex space-x-8">
                    <button wire:click="setActiveTab('overview')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'overview' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Overview
                    </button>
                    <button wire:click="setActiveTab('gigs')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'gigs' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        My Gigs
                    </button>
                    <button wire:click="setActiveTab('applications')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'applications' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Applications
                    </button>
                </nav>
            </div>

            <!-- Overview Tab -->
            @if($activeTab === 'overview')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-sm font-medium text-gray-500">Total Gigs</div>
                        <div class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_gigs'] }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-sm font-medium text-gray-500">Active Gigs</div>
                        <div class="text-3xl font-bold text-green-600 mt-2">{{ $stats['active_gigs'] }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-sm font-medium text-gray-500">Pending Applications</div>
                        <div class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['pending_applications'] }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-sm font-medium text-gray-500">Accepted Applications</div>
                        <div class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['accepted_applications'] }}</div>
                    </div>
                </div>
            @endif

            <!-- Gigs Tab -->
            @if($activeTab === 'gigs')
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <input type="text" wire:model.live="searchTerm" placeholder="Search gigs..." 
                                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <select wire:model.live="filterStatus" class="px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="all">All Status</option>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                                <option value="in_progress">In Progress</option>
                            </select>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-200">
                        @forelse($gigs as $gig)
                            <div class="p-6 hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $gig->title }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">{{ Str::limit($gig->description, 150) }}</p>
                                        <div class="flex items-center gap-4 mt-3">
                                            <span class="text-sm text-gray-600">Budget: ${{ number_format($gig->budget, 2) }}</span>
                                            <span class="text-sm text-gray-600">Applications: {{ $gig->applications_count }}</span>
                                            @if($gig->pending_applications_count > 0)
                                                <span class="text-sm font-medium text-orange-600">{{ $gig->pending_applications_count }} pending</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-2 ml-4">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            {{ $gig->status->value === 'open' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $gig->status->value === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $gig->status->value === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}">
                                            {{ ucfirst($gig->status->value) }}
                                        </span>
                                        <div class="flex gap-2">
                                            <button wire:click="toggleGigStatus({{ $gig->id }})" 
                                                    class="text-sm text-indigo-600 hover:text-indigo-800">
                                                {{ $gig->status->value === 'open' ? 'Close' : 'Reopen' }}
                                            </button>
                                            <button type="button"
                                                    wire:click="openInHouseModal({{ $gig->id }})" 
                                                    class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                                                Assign In-House
                                            </button>
                                            <button wire:click="deleteGig({{ $gig->id }})" 
                                                    wire:confirm="Are you sure you want to delete this gig?"
                                                    class="text-sm text-red-600 hover:text-red-800">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center text-gray-500">
                                <p>No gigs found.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($gigs->hasPages())
                        <div class="p-6 border-t border-gray-200">
                            {{ $gigs->links() }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Applications Tab -->
            @if($activeTab === 'applications')
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <select wire:model.live="filterGigForApplications" class="px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="all">All Gigs</option>
                            @foreach($gigsForFilter as $gig)
                                <option value="{{ $gig->id }}">{{ $gig->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="divide-y divide-gray-200">
                        @forelse($applications as $application)
                            <div class="p-6 hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $application->freelancer->name }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">Applied to: {{ $application->gig->title }}</p>
                                        <p class="text-sm text-gray-500 mt-2">{{ Str::limit($application->cover_letter, 200) }}</p>
                                        <div class="flex items-center gap-4 mt-3">
                                            <span class="text-sm text-gray-600">Proposed Rate: ${{ number_format($application->proposed_rate, 2) }}</span>
                                            <span class="text-sm text-gray-500">{{ $application->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-2 ml-4">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $application->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                        @if($application->status === 'pending')
                                            <button wire:click="viewApplication({{ $application->id }})" 
                                                    class="text-sm text-indigo-600 hover:text-indigo-800">
                                                View Details
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center text-gray-500">
                                <p>No applications found.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($applications->hasPages())
                        <div class="p-6 border-t border-gray-200">
                            {{ $applications->links() }}
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>

    <!-- Application Details Modal -->
    @if($showApplicationModal && $selectedApplication)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="closeApplicationModal">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto" wire:click.stop>
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Application Details</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-900">Freelancer: {{ $selectedApplication->freelancer->name }}</h4>
                        <p class="text-sm text-gray-600">{{ $selectedApplication->freelancer->email }}</p>
                    </div>
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-900">Gig: {{ $selectedApplication->gig->title }}</h4>
                        <p class="text-sm text-gray-600">Proposed Rate: ${{ number_format($selectedApplication->proposed_rate, 2) }}</p>
                    </div>
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">Cover Letter</h4>
                        <p class="text-gray-700">{{ $selectedApplication->cover_letter }}</p>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 flex justify-end gap-3">
                    <button wire:click="closeApplicationModal" 
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                        Close
                    </button>
                    <button wire:click="rejectApplication({{ $selectedApplication->id }})" 
                            wire:confirm="Are you sure you want to reject this application?"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Reject
                    </button>
                    <button wire:click="acceptApplication({{ $selectedApplication->id }})" 
                            wire:confirm="This will reject all other applications for this gig. Continue?"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Accept
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- In-House Assignment Modal -->
    @if($selectedGigId)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" 
         wire:click="$set('selectedGigId', null)">
        
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4" wire:click.stop>
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Assign to In-House Developer</h3>
                <p class="text-sm text-gray-600 mt-2">Submit a request to assign this gig to an in-house developer. Our team will review and assign a developer soon.</p>
            </div>
            
            <div class="p-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Additional Notes (Optional)
                </label>
                <textarea 
                    wire:model="inhouseNotes"
                    rows="4"
                    placeholder="Any specific requirements or preferences..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                ></textarea>
            </div>
            
            <div class="p-6 border-t border-gray-200 flex justify-end gap-3">
                <button wire:click="$set('selectedGigId', null)" 
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Cancel
                </button>
                <button wire:click="assignToInHouseDeveloper" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Submit Request
                </button>
            </div>
        </div>
    </div>
    @endif
</div>