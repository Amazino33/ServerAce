<div class="bg-white rounded-xl shadow-lg">
    <!-- Filters -->
    <div class="p-6 border-b">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <input type="text" 
                       wire:model.live.debounce.300ms="searchTerm"
                       placeholder="Search gigs by title..."
                       class="w-full px-4 py-2 border-2 rounded-lg focus:border-green-500 focus:outline-none">
            </div>

            <!-- Status Filter -->
            <div>
                <select wire:model.live="filterStatus" 
                        class="w-full px-4 py-2 border-2 rounded-lg focus:border-green-500 focus:outline-none">
                    <option value="all">All Status</option>
                    <option value="open">Open</option>
                    <option value="closed">Closed</option>
                    <option value="in_progress">In Progress</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Gigs List -->
    <div class="divide-y">
        @forelse($gigs as $gig)
            <div class="p-6 hover:bg-gray-50 transition">
                <div class="flex gap-6">
                    <!-- Gig Image -->
                    <div class="flex-shrink-0">
                        @if($gig->hasMedia('images'))
                            <img src="{{ $gig->getFirstMediaUrl('images') }}" 
                                 class="w-32 h-32 object-cover rounded-xl shadow"
                                 alt="{{ $gig->title }}">
                        @else
                            <div class="w-32 h-32 bg-gradient-to-br from-gray-200 to-gray-300 rounded-xl flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-3xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Gig Details -->
                    <div class="flex-1">
                        <!-- Title & Category -->
                        <div class="mb-3">
                            <a href="{{ route('gigs.show', $gig->slug) }}" 
                               target="_blank"
                               class="text-xl font-bold text-gray-900 hover:text-green-600 inline-flex items-center gap-2">
                                {{ $gig->title }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                            @if($gig->category)
                                <span class="ml-3 text-sm bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                    {{ $gig->category->name }}
                                </span>
                            @endif
                        </div>

                        <!-- Description Preview -->
                        <p class="text-gray-600 mb-3 line-clamp-2">
                            {{ Str::limit($gig->description, 150) }}
                        </p>

                        <!-- Meta Info -->
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4">
                            <!-- Budget -->
                            <span class="font-semibold text-green-600">
                                <i class="fas fa-dollar-sign mr-1"></i>
                                @if($gig->budget_fixed)
                                    ${{ number_format($gig->budget_fixed, 2) }}
                                @else
                                    ${{ number_format($gig->budget_min, 2) }} - ${{ number_format($gig->budget_max, 2) }}
                                @endif
                            </span>

                            <!-- Applications -->
                            <span>
                                <i class="fas fa-inbox mr-1"></i>
                                {{ $gig->applications_count }} applications
                            </span>

                            <!-- Pending -->
                            @if($gig->pending_applications_count > 0)
                                <span class="text-yellow-600 font-semibold">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $gig->pending_applications_count }} pending
                                </span>
                            @endif

                            <!-- Date -->
                            <span>
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $gig->created_at->format('M d, Y') }}
                            </span>

                            <!-- Status -->
                            @php
                                $status = is_object($gig->status) ? $gig->status->value : $gig->status;
                            @endphp
                            <span class="px-3 py-1 rounded-full font-semibold
                                @if($status === 'open') bg-green-100 text-green-800
                                @elseif($status === 'closed') bg-gray-100 text-gray-800
                                @else bg-blue-100 text-blue-800
                                @endif">
                                {{ ucfirst($status) }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="{{ route('gigs.show', $gig->slug) }}" 
                               target="_blank"
                               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>

                            @php
                                $status = is_object($gig->status) ? $gig->status->value : $gig->status;
                            @endphp

                            @if($status === 'open')
                                <button wire:click="toggleGigStatus({{ $gig->id }})"
                                        wire:confirm="Close this gig? It will stop accepting new applications."
                                        class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm rounded-lg transition">
                                    <i class="fas fa-pause mr-1"></i> Close
                                </button>
                            @elseif($status === 'closed')
                                <button wire:click="toggleGigStatus({{ $gig->id }})"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition">
                                    <i class="fas fa-play mr-1"></i> Reopen
                                </button>
                            @endif

                            @if($gig->applications_count > 0)
                                <button wire:click="$set('filterGigForApplications', {{ $gig->id }}); setActiveTab('applications')"
                                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition">
                                    <i class="fas fa-inbox mr-1"></i> View Applications
                                </button>
                            @endif

                            <button wire:click="deleteGig({{ $gig->id }})"
                                    wire:confirm="Are you sure you want to delete this gig? This action cannot be undone."
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 text-lg mb-4">
                    @if($searchTerm || $filterStatus !== 'all')
                        No gigs found matching your filters
                    @else
                        You haven't posted any gigs yet
                    @endif
                </p>
                @if(!$searchTerm && $filterStatus === 'all')
                    <livewire:client.create-gig />
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($gigs->hasPages())
        <div class="p-6 border-t">
            {{ $gigs->links() }}
        </div>
    @endif
</div>