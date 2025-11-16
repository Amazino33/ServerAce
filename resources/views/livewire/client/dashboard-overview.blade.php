<div class="space-y-6">
    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <livewire:client.create-gig />
            
            <button wire:click="setActiveTab('gigs')" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg transition text-left">
                <i class="fas fa-briefcase text-2xl mb-2"></i>
                <p class="text-lg">View My Gigs</p>
                <p class="text-sm opacity-90">Manage {{ $stats['total_gigs'] }} gigs</p>
            </button>

            <button wire:click="setActiveTab('applications')" 
                    class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg transition text-left">
                <i class="fas fa-inbox text-2xl mb-2"></i>
                <p class="text-lg">View Applications</p>
                <p class="text-sm opacity-90">{{ $stats['pending_applications'] }} pending</p>
            </button>
        </div>
    </div>

    <!-- Recent Gigs -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900">Recent Gigs</h2>
            <button wire:click="setActiveTab('gigs')" class="text-green-600 hover:text-green-700 font-semibold text-sm">
                View All →
            </button>
        </div>

        @php
            $recentGigs = App\Models\Gig::where('client_id', auth()->id())
                ->withCount('applications', 'pendingApplications')
                ->with(['category', 'media'])
                ->latest()
                ->take(5)
                ->get();
        @endphp

        @if($recentGigs->isEmpty())
            <div class="text-center py-12">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 text-lg mb-4">You haven't posted any gigs yet</p>
                <livewire:client.create-gig />
            </div>
        @else
            <div class="space-y-4">
                @foreach($recentGigs as $gig)
                    <div class="border rounded-xl p-4 hover:shadow-md transition">
                        <div class="flex items-center gap-4">
                            <!-- Gig Image -->
                            @if($gig->hasMedia('images'))
                                <img src="{{ $gig->getFirstMediaUrl('images') }}" 
                                     class="w-20 h-20 object-cover rounded-lg"
                                     alt="{{ $gig->title }}">
                            @else
                                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                </div>
                            @endif

                            <!-- Gig Info -->
                            <div class="flex-1">
                                <a href="{{ route('gigs.show', $gig->slug) }}" 
                                   class="text-lg font-bold text-gray-900 hover:text-green-600">
                                    {{ $gig->title }}
                                </a>
                                <div class="flex items-center gap-4 mt-2 text-sm text-gray-600">
                                    <span>
                                        <i class="fas fa-inbox mr-1"></i>
                                        {{ $gig->applications_count }} applications
                                    </span>
                                    @if($gig->pending_applications_count > 0)
                                        <span class="text-yellow-600 font-semibold">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $gig->pending_applications_count }} pending
                                        </span>
                                    @endif
                                    <span>
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $gig->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div>
                                @php
                                    $status = is_object($gig->status) ? $gig->status->value : $gig->status;
                                @endphp
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    @if($status === 'open') bg-green-100 text-green-800
                                    @elseif($status === 'closed') bg-gray-100 text-gray-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ ucfirst($status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Recent Applications -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900">Recent Applications</h2>
            <button wire:click="setActiveTab('applications')" class="text-green-600 hover:text-green-700 font-semibold text-sm">
                View All →
            </button>
        </div>

        @php
            $recentApplications = App\Models\GigApplication::whereHas('gig', function($q) {
                    $q->where('client_id', auth()->id());
                })
                ->with(['freelancer', 'gig'])
                ->latest()
                ->take(5)
                ->get();
        @endphp

        @if($recentApplications->isEmpty())
            <div class="text-center py-12">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-gray-500 text-lg">No applications yet</p>
                <p class="text-gray-400 text-sm mt-2">Applications will appear here once freelancers apply to your gigs</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($recentApplications as $application)
                    <div class="border rounded-xl p-4 hover:shadow-md transition cursor-pointer"
                         wire:click="viewApplication({{ $application->id }})">
                        <div class="flex items-center justify-between">
                            <!-- Freelancer Info -->
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center text-white font-bold">
                                    {{ substr($application->freelancer->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $application->freelancer->name }}</p>
                                    <p class="text-sm text-gray-600">Applied to: {{ Str::limit($application->gig->title, 40) }}</p>
                                </div>
                            </div>

                            <!-- Price & Status -->
                            <div class="text-right">
                                <p class="text-lg font-bold text-green-600">
                                    ${{ number_format($application->proposed_price, 2) }}
                                </p>
                                <span class="text-xs px-2 py-1 rounded-full
                                    @if($application->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($application->status === 'accepted') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Activity Chart (Optional - Placeholder) -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Activity Overview</h2>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-xl">
            <div class="text-center">
                <i class="fas fa-chart-line text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Chart Coming Soon</p>
                <p class="text-sm text-gray-400 mt-2">View your gig performance and application trends</p>
            </div>
        </div>
    </div>
</div>