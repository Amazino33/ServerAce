<div class="bg-white rounded-xl shadow-lg">
    <!-- Filters -->
    <div class="p-6 border-b">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Filter by Gig -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Filter by Gig</label>
                <select wire:model.live="filterGigForApplications" 
                        class="w-full px-4 py-2 border-2 rounded-lg focus:border-green-500 focus:outline-none">
                    <option value="all">All Gigs</option>
                    @foreach($gigsForFilter as $gig)
                        <option value="{{ $gig->id }}">{{ $gig->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Stats Summary -->
            <div class="flex items-end justify-end gap-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ $applications->total() }}</p>
                    <p class="text-xs text-gray-600">Total</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_applications'] }}</p>
                    <p class="text-xs text-gray-600">Pending</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $stats['accepted_applications'] }}</p>
                    <p class="text-xs text-gray-600">Accepted</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications List -->
    <div class="divide-y">
        @forelse($applications as $application)
            <div class="p-6 hover:bg-gray-50 transition cursor-pointer"
                 wire:click="viewApplication({{ $application->id }})">
                <div class="flex items-center gap-6">
                    <!-- Freelancer Avatar -->
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                            {{ substr($application->freelancer->name, 0, 1) }}
                        </div>
                    </div>

                    <!-- Application Details -->
                    <div class="flex-1 min-w-0">
                        <!-- Freelancer Name -->
                        <h3 class="text-lg font-bold text-gray-900 mb-1">
                            {{ $application->freelancer->name }}
                        </h3>

                        <!-- Gig Title -->
                        <p class="text-sm text-gray-600 mb-2">
                            Applied to: 
                            <a href="{{ route('gigs.show', $application->gig->slug) }}" 
                               target="_blank"
                               class="text-green-600 hover:text-green-700 font-semibold"
                               onclick="event.stopPropagation()">
                                {{ $application->gig->title }} ↗
                            </a>
                        </p>

                        <!-- Cover Letter Preview -->
                        <p class="text-sm text-gray-600 line-clamp-2 mb-2">
                            {{ Str::limit($application->cover_letter, 120) }}
                        </p>

                        <!-- Meta Info -->
                        <div class="flex items-center gap-4 text-xs text-gray-500">
                            <span>
                                <i class="fas fa-envelope mr-1"></i>
                                {{ $application->freelancer->email }}
                            </span>
                            <span>
                                <i class="fas fa-clock mr-1"></i>
                                {{ $application->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <!-- Price & Status -->
                    <div class="flex-shrink-0 text-right">
                        <!-- Proposed Price -->
                        <div class="mb-3">
                            <p class="text-sm text-gray-600">Proposed Price</p>
                            <p class="text-2xl font-bold text-green-600">
                                ${{ number_format($application->proposed_price, 2) }}
                            </p>
                        </div>

                        <!-- Status Badge -->
                        <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold
                            @if($application->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($application->status === 'accepted') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex-shrink-0">
                        @if($application->status === 'pending')
                            <div class="flex flex-col gap-2">
                                <button wire:click.stop="acceptApplication({{ $application->id }})"
                                        wire:confirm="Accept this application? This will reject all other applications for this gig."
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition whitespace-nowrap">
                                    <i class="fas fa-check mr-1"></i> Accept
                                </button>
                                <button wire:click.stop="rejectApplication({{ $application->id }})"
                                        wire:confirm="Reject this application?"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition whitespace-nowrap">
                                    <i class="fas fa-times mr-1"></i> Reject
                                </button>
                            </div>
                        @else
                            <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition">
                                <i class="fas fa-eye mr-1"></i> View Details
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-gray-500 text-lg mb-2">
                    @if($filterGigForApplications !== 'all')
                        No applications for this gig yet
                    @else
                        No applications received yet
                    @endif
                </p>
                <p class="text-gray-400 text-sm">
                    Freelancers will appear here when they apply to your gigs
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($applications->hasPages())
        <div class="p-6 border-t">
            {{ $applications->links() }}
        </div>
    @endif
</div>