<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4">
            
            <!-- Breadcrumb -->
            <nav class="mb-6 text-sm">
                <ol class="flex items-center space-x-2 text-gray-600">
                    <li>
                        <a href="{{ route('gigs.index') }}" class="hover:text-green-600 transition">
                            <i class="fas fa-home mr-1"></i>Browse Gigs
                        </a>
                    </li>
                    <li class="text-gray-400">/</li>
                    @if($gig->category)
                        <li>
                            <a href="{{ route('gigs.index') }}?category={{ $gig->category->slug }}" 
                               class="hover:text-green-600 transition">
                                {{ $gig->category->name }}
                            </a>
                        </li>
                        <li class="text-gray-400">/</li>
                    @endif
                    <li class="text-gray-900 font-semibold truncate max-w-md">{{ Str::limit($gig->title, 50) }}</li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Main Card -->
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <!-- Featured Image -->
                        @if($gig->hasMedia('images'))
                            <div class="relative group">
                                <img src="{{ $gig->getFirstMediaUrl('images') }}"
                                     alt="{{ $gig->title }}"
                                     class="w-full h-96 object-cover">
                                
                                <!-- Image Overlay on Hover -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                    <button class="opacity-0 group-hover:opacity-100 bg-white text-gray-900 px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform scale-90 group-hover:scale-100">
                                        <i class="fas fa-expand-alt mr-2"></i>View Full Image
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="bg-gradient-to-br from-gray-200 to-gray-300 w-full h-96 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-gray-500 text-lg font-semibold">No Image Available</span>
                                </div>
                            </div>
                        @endif

                        <!-- Image Gallery Thumbnails -->
                        @if($gig->getMedia('images')->count() > 1)
                            <div class="p-4 bg-gray-50 border-t">
                                <p class="text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-images mr-2"></i>Gallery ({{ $gig->getMedia('images')->count() }} images)
                                </p>
                                <div class="grid grid-cols-5 gap-2">
                                    @foreach($gig->getMedia('images') as $image)
                                        <img src="{{ $image->getUrl() }}" 
                                             alt="{{ $gig->title }}"
                                             class="w-full h-24 object-cover rounded-lg cursor-pointer hover:opacity-75 hover:ring-2 hover:ring-green-500 transition">
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="p-8">
                            <!-- Title -->
                            <h1 class="text-4xl font-bold text-gray-900 mb-6 leading-tight">{{ $gig->title }}</h1>

                            <!-- Client Info -->
                            <x-client-info :gig="$gig" />

                            <!-- Category & Tags -->
                            <div class="mb-8">
                                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">
                                    <i class="fas fa-tag mr-2"></i>Category
                                </h3>
                                @if($gig->category)
                                    <a href="{{ route('gigs.index') }}?category={{ $gig->category->slug }}"
                                       class="inline-flex items-center bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-green-200 transition shadow-sm">
                                        <i class="fas fa-folder mr-2"></i>
                                        {{ $gig->category->name }}
                                    </a>
                                @else
                                    <span class="inline-flex items-center bg-gray-100 text-gray-600 px-4 py-2 rounded-full text-sm">
                                        <i class="fas fa-question-circle mr-2"></i>Uncategorized
                                    </span>
                                @endif
                            </div>

                            <!-- Description -->
                            <div class="mb-8">
                                <h3 class="text-2xl font-bold mb-4 text-gray-900 flex items-center">
                                    <i class="fas fa-file-alt text-green-600 mr-3"></i>
                                    About This Gig
                                </h3>
                                <div class="prose prose-lg max-w-none">
                                    <div class="text-gray-700 leading-relaxed whitespace-pre-line bg-gray-50 rounded-xl p-6 border-l-4 border-green-500">
                                        {{ $gig->description }}
                                    </div>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <x-status-badge :gig="$gig" />
                        </div>
                    </div>

                    <!-- Additional Info Card -->
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Additional Information
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-eye text-blue-600 text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Views</p>
                                    <p class="text-xl font-bold text-gray-900">{{ $gig->views ?? 0 }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-users text-purple-600 text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Applications</p>
                                    <p class="text-xl font-bold text-gray-900">{{ $gig->applications_count ?? 0 }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-calendar-check text-green-600 text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Posted</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $gig->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-yellow-600 text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Last Updated</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $gig->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Share Card -->
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            <i class="fas fa-share-alt text-green-600 mr-2"></i>
                            Share This Gig
                        </h3>
                        <div class="flex gap-3">
                            <button class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg font-semibold transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fab fa-twitter mr-2"></i>Twitter
                            </button>
                            <button class="flex-1 bg-blue-700 hover:bg-blue-800 text-white py-3 rounded-lg font-semibold transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fab fa-facebook mr-2"></i>Facebook
                            </button>
                            <button class="flex-1 bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                            </button>
                            <button class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-3 rounded-lg font-semibold transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-link mr-2"></i>Copy Link
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-4 space-y-6">
                        
                        <!-- Budget Card -->
                        <div class="bg-white rounded-2xl shadow-xl p-6">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4 flex items-center">
                                <i class="fas fa-dollar-sign mr-2"></i>Budget
                            </h3>
                            <div class="text-center py-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border-2 border-green-200 mb-6">
                                @if($gig->budget_fixed)
                                    <div class="text-4xl font-bold text-green-600 mb-2">
                                        ${{ number_format($gig->budget_fixed, 2) }}
                                    </div>
                                    <p class="text-sm text-gray-600 font-semibold">Fixed Price</p>
                                @else
                                    <div class="text-3xl font-bold text-green-600 mb-2">
                                        ${{ number_format($gig->budget_min, 2) }} - ${{ number_format($gig->budget_max, 2) }}
                                    </div>
                                    <p class="text-sm text-gray-600 font-semibold">Budget Range</p>
                                @endif
                            </div>

                            <!-- Apply Component -->
                            <livewire:apply-to-gig :gig="$gig" :key="'apply-'.$gig->id" />
                        </div>

                        <!-- Quick Stats Card -->
                        <div class="bg-white rounded-2xl shadow-xl p-6">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4 flex items-center">
                                <i class="fas fa-chart-bar mr-2"></i>Quick Stats
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-3 border-b">
                                    <span class="text-gray-600 flex items-center">
                                        <i class="fas fa-calendar-alt w-5 mr-2 text-gray-400"></i>
                                        Posted
                                    </span>
                                    <span class="font-semibold text-gray-900">
                                        {{ $gig->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center justify-between py-3 border-b">
                                    <span class="text-gray-600 flex items-center">
                                        <i class="fas fa-eye w-5 mr-2 text-gray-400"></i>
                                        Views
                                    </span>
                                    <span class="font-semibold text-gray-900">
                                        {{ $gig->views ?? 0 }}
                                    </span>
                                </div>

                                @if($gig->applications_count ?? false)
                                    <div class="flex items-center justify-between py-3 border-b">
                                        <span class="text-gray-600 flex items-center">
                                            <i class="fas fa-users w-5 mr-2 text-gray-400"></i>
                                            Applications
                                        </span>
                                        <span class="font-semibold text-gray-900">
                                            {{ $gig->applications_count }}
                                        </span>
                                    </div>
                                @endif

                                <div class="flex items-center justify-between py-3">
                                    <span class="text-gray-600 flex items-center">
                                        <i class="fas fa-clock w-5 mr-2 text-gray-400"></i>
                                        Activity
                                    </span>
                                    <span class="font-semibold text-gray-900">
                                        {{ $gig->updated_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Report Card -->
                        <div class="bg-white rounded-2xl shadow-xl p-6">
                            <button class="w-full text-left flex items-center justify-between text-gray-600 hover:text-red-600 transition group">
                                <span class="flex items-center">
                                    <i class="fas fa-flag mr-3 group-hover:animate-pulse"></i>
                                    Report this gig
                                </span>
                                <i class="fas fa-chevron-right text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Link -->
            <div class="mt-8 text-center">
                <a href="{{ route('gigs.index') }}" 
                   class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold transition group">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Browse Gigs
                </a>
            </div>
        </div>
    </div>
</x-app-layout>