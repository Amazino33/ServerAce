<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">In-House Developer Assignments</h1>
            <p class="text-gray-600 mt-1">Manage gig assignments to in-house developers</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Requests -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Total Requests</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_requests'] }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Pending Assignment</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_requests'] }}</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Assigned Requests -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Assigned</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['assigned_requests'] }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                    <input wire:model.live="searchTerm"
                           type="text"
                           placeholder="Search by gig title or client name..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Filter Status</label>
                    <select wire:model.live="filterStatus"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="all">All Requests</option>
                        <option value="pending">Pending Assignment</option>
                        <option value="assigned">Assigned</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Requests Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Gig Title</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Client</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Category</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Developer</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Requested</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <a href="{{ route('gigs.show', $request->slug) }}"
                                       target="_blank"
                                       class="text-purple-600 hover:text-purple-700 font-medium">
                                        {{ Str::limit($request->title, 30) }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        <p class="font-medium text-gray-900">{{ $request->client->name }}</p>
                                        <p class="text-gray-500">{{ $request->client->email }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">
                                        {{ $request->category->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($request->inHouseDeveloper)
                                        <div class="text-sm">
                                            <p class="font-medium text-gray-900">{{ $request->inHouseDeveloper->name }}</p>
                                            <p class="text-gray-500">{{ $request->inHouseDeveloper->email }}</p>
                                        </div>
                                    @else
                                        <span class="text-gray-500 text-sm">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600">
                                        {{ $request->inhouse_assigned_at?->diffForHumans() ?? $request->created_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($request->inHouseDeveloper)
                                        <span class="inline-flex px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                            <i class="fas fa-check mr-1"></i> Assigned
                                        </span>
                                    @else
                                        <span class="inline-flex px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <button wire:click="viewGigDetails({{ $request->id }})"
                                                class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition">
                                            <i class="fas fa-edit mr-1"></i> Assign
                                        </button>
                                        @if($request->inHouseDeveloper)
                                            <button wire:click="removeAssignment({{ $request->id }})"
                                                    wire:confirm="Remove this assignment?"
                                                    class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition">
                                                <i class="fas fa-trash mr-1"></i> Remove
                                            </button>
                                        @else
                                            <button wire:click="cancelInHouseRequest({{ $request->id }})"
                                                    wire:confirm="Cancel this request?"
                                                    class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg transition">
                                                <i class="fas fa-times mr-1"></i> Cancel
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-3 block opacity-50"></i>
                                    <p>No in-house developer requests found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($requests->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>

        <!-- Assignment Modal -->
        @if($showAssignmentModal && $selectedGig)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
                    
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 px-6 py-4 rounded-t-2xl flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-white">Assign Developer</h3>
                        <button wire:click="closeAssignmentModal" class="text-white hover:text-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-8">
                        <!-- Gig Info -->
                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                            <h4 class="font-semibold text-gray-700 mb-2">Gig Details</h4>
                            <p class="text-lg font-bold text-gray-900 mb-2">{{ $selectedGig->title }}</p>
                            <p class="text-sm text-gray-600 mb-2">
                                <strong>Client:</strong> {{ $selectedGig->client->name }} ({{ $selectedGig->client->email }})
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Budget:</strong> {{ $selectedGig->budget_display }}
                            </p>
                        </div>

                        <!-- Assignment Notes -->
                        @if($selectedGig->inhouse_assignment_notes)
                            <div class="bg-purple-50 rounded-xl p-4 mb-6 border border-purple-200">
                                <h4 class="font-semibold text-gray-700 mb-2">Client Notes</h4>
                                <p class="text-gray-700 whitespace-pre-line">{{ $selectedGig->inhouse_assignment_notes }}</p>
                            </div>
                        @endif

                        <!-- Developer Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Select Developer to Assign</label>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                @foreach($developers as $dev)
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-purple-50 transition">
                                        <input type="radio" 
                                               wire:model="selectedDeveloperId" 
                                               value="{{ $dev->id }}"
                                               class="mr-3 w-4 h-4">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $dev->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $dev->email }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4">
                            <button wire:click="assignDeveloper"
                                    class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-xl transition">
                                <i class="fas fa-check mr-2"></i> Confirm Assignment
                            </button>
                            <button wire:click="closeAssignmentModal"
                                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-xl transition">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
