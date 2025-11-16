@props(['gig', 'viewMode' => 'grid'])

@php
    $status = is_object($gig->status) ? $gig->status->value : $gig->status;
@endphp

@if($viewMode === 'list')
    {{-- LIST VIEW --}}
    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden group">
        <div class="flex">
            <!-- Image -->
            <div class="w-64 flex-shrink-0 relative overflow-hidden">
                @if($gig->hasMedia('images'))
                    <img src="{{ $gig->getFirstMediaUrl('images') }}" 
                         alt="{{ $gig->title }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="absolute top-3 right-3">
                    @if($status === 'open')
                        <span class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-bold shadow-lg">
                            <i class="fas fa-circle text-xs animate-pulse mr-1"></i>Open
                        </span>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 p-6 flex flex-col">
                <!-- Category -->
                @if($gig->category)
                    <span class="inline-block w-fit px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold mb-3">
                        {{ $gig->category->name }}
                    </span>
                @endif

                <!-- Title -->
                <a href="{{ route('gigs.show', $gig->slug) }}" 
                   class="text-xl font-bold text-gray-900 hover:text-green-600 mb-3 line-clamp-2 transition">
                    {{ $gig->title }}
                </a>

                <!-- Description -->
                <p class="text-gray-600 mb-4 line-clamp-2 flex-grow">
                    {{ Str::limit($gig->description, 120) }}
                </p>

                <!-- Footer -->
                <div class="flex items-center justify-between pt-4 border-t">
                    <!-- Client Info -->
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center text-white text-sm font-bold">
                            {{ substr($gig->client->name ?? 'U', 0, 1) }}
                        </div>
                        <span class="text-sm text-gray-600">{{ $gig->client->name ?? 'Unknown' }}</span>
                    </div>

                    <!-- Price -->
                    <div class="text-right">
                        <div class="text-2xl font-bold text-green-600">
                            @if($gig->budget_fixed)
                                ${{ number_format($gig->budget_fixed, 0) }}
                            @else
                                ${{ number_format($gig->budget_min, 0) }}+
                            @endif
                        </div>
                        <div class="text-xs text-gray-500">
                            @if($gig->budget_fixed)
                                Fixed Price
                            @else
                                Starting at
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    {{-- GRID VIEW --}}
    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden group flex flex-col h-full">
        <!-- Image -->
        <div class="relative overflow-hidden h-56">
            @if($gig->hasMedia('images'))
                <img src="{{ $gig->getFirstMediaUrl('images') }}" 
                     alt="{{ $gig->title }}"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            @else
                <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-5xl"></i>
                </div>
            @endif
            
            <!-- Status Badge -->
            <div class="absolute top-3 right-3">
                @if($status === 'open')
                    <span class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-bold shadow-lg backdrop-blur-sm">
                        <i class="fas fa-circle text-xs animate-pulse mr-1"></i>Open
                    </span>
                @elseif($status === 'in_progress')
                    <span class="px-3 py-1 bg-blue-500 text-white rounded-full text-xs font-bold shadow-lg">
                        In Progress
                    </span>
                @endif
            </div>

            <!-- Category Badge -->
            @if($gig->category)
                <div class="absolute top-3 left-3">
                    <span class="px-3 py-1 bg-white bg-opacity-90 text-gray-800 rounded-full text-xs font-semibold shadow">
                        {{ $gig->category->name }}
                    </span>
                </div>
            @endif

            <!-- Quick View Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                <a href="{{ route('gigs.show', $gig->slug) }}"
                   class="opacity-0 group-hover:opacity-100 bg-white text-gray-900 px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform scale-90 group-hover:scale-100 shadow-xl">
                    <i class="fas fa-eye mr-2"></i>View Details
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 flex flex-col flex-grow">
            <!-- Client Info -->
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center text-white text-sm font-bold">
                    {{ substr($gig->client->name ?? 'U', 0, 1) }}
                </div>
                <span class="text-sm text-gray-600 font-medium">{{ $gig->client->name ?? 'Unknown' }}</span>
            </div>

            <!-- Title -->
            <a href="{{ route('gigs.show', $gig->slug) }}" 
               class="text-lg font-bold text-gray-900 hover:text-green-600 mb-3 line-clamp-2 transition">
                {{ $gig->title }}
            </a>

            <!-- Description -->
            <p class="text-sm text-gray-600 mb-4 line-clamp-3 flex-grow">
                {{ Str::limit($gig->description, 100) }}
            </p>

            <!-- Stats -->
            <div class="flex items-center gap-4 text-xs text-gray-500 mb-4 pb-4 border-b">
                <span class="flex items-center">
                    <i class="fas fa-eye mr-1"></i>
                    {{ $gig->views ?? 0 }}
                </span>
                <span class="flex items-center">
                    <i class="fas fa-inbox mr-1"></i>
                    {{ $gig->applications_count ?? 0 }}
                </span>
                <span class="flex items-center">
                    <i class="fas fa-clock mr-1"></i>
                    {{ $gig->created_at->diffForHumans() }}
                </span>
            </div>

            <!-- Footer - Price & CTA -->
            <div class="flex items-center justify-between">
                <!-- Price -->
                <div>
                    <div class="text-xs text-gray-500 mb-1">
                        @if($gig->budget_fixed)
                            Fixed Price
                        @else
                            Starting at
                        @endif
                    </div>
                    <div class="text-2xl font-bold text-green-600">
                        @if($gig->budget_fixed)
                            ${{ number_format($gig->budget_fixed, 0) }}
                        @else
                            ${{ number_format($gig->budget_min, 0) }}
                        @endif
                    </div>
                </div>

                <!-- View Button -->
                <a href="{{ route('gigs.show', $gig->slug) }}"
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold text-sm transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    View Gig
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
@endif