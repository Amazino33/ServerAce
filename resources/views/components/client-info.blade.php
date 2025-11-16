@props(['gig'])

<div class="flex items-center gap-4 mb-8 pb-6 border-b">
    @if($gig->client)
        <!-- Avatar -->
        <div class="relative">
            @if($gig->client->avatar ?? false)
                <img src="{{ $gig->client->avatar }}" 
                     class="w-16 h-16 rounded-full border-2 border-green-500 object-cover"
                     alt="{{ $gig->client->name }}">
            @else
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center text-white text-2xl font-bold border-2 border-green-500">
                    {{ substr($gig->client->name, 0, 1) }}
                </div>
            @endif
            <!-- Online Status Indicator (optional) -->
            <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
        </div>

        <!-- Client Details -->
        <div class="flex-1">
            <p class="text-lg font-semibold text-gray-900">{{ $gig->client->name }}</p>
            <div class="flex items-center gap-3 mt-1 text-sm text-gray-600">
                <span>
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Posted {{ $gig->created_at->diffForHumans() }}
                </span>
                <span class="text-gray-400">•</span>
                <span>
                    <i class="fas fa-briefcase mr-1"></i>
                    {{ $gig->client->postedGigs()->count() ?? 0 }} gigs posted
                </span>
            </div>
        </div>

        <!-- Contact Button (optional) -->
        @auth
            @if(auth()->id() !== $gig->client_id)
                <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition text-sm font-semibold">
                    <i class="fas fa-envelope mr-2"></i>Contact
                </button>
            @endif
        @endauth
    @else
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                <i class="fas fa-user text-gray-400 text-xl"></i>
            </div>
            <p class="text-red-600 font-semibold">Client information unavailable</p>
        </div>
    @endif
</div>