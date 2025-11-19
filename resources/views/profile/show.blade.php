<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4">
            
            <!-- Hero Section -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl shadow-lg p-8 text-white mb-8">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                    <!-- Avatar -->
                    <div class="relative">
                        <img src="{{ $user->avatar_url }}" 
                             alt="{{ $user->name }}" 
                             class="w-32 h-32 rounded-full border-4 border-white shadow-lg">
                        @if($user->role->value === 'freelancer' && $user->stripe_onboarded)
                            <div class="absolute -bottom-2 -right-2 bg-green-500 text-white rounded-full p-2 border-2 border-white">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- User Info -->
                    <div class="flex-1 text-center sm:text-left">
                        <h1 class="text-3xl font-bold mb-2">{{ $user->name }}</h1>
                        <div class="flex flex-wrap items-center gap-3 justify-center sm:justify-start mb-3">
                            <span class="px-4 py-1.5 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-sm font-semibold">
                                <i class="fas fa-user mr-1"></i>
                                {{ ucfirst($user->role->value) }}
                            </span>
                            @if($user->role->value === 'freelancer' && $user->stripe_onboarded)
                                <span class="px-4 py-1.5 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-sm font-semibold">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Payment Verified
                                </span>
                            @endif
                            <span class="flex items-center gap-1 text-green-100 text-sm">
                                <i class="fas fa-calendar-alt"></i>
                                Member since {{ $user->created_at->format('M Y') }}
                            </span>
                        </div>

                        <!-- Rating Stars -->
                        @if($stats['rating_count'] > 0)
                            <div class="flex items-center gap-2 justify-center sm:justify-start">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $stats['rating_avg'] ? 'text-yellow-400' : 'text-white text-opacity-30' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-green-100 text-sm">
                                    {{ number_format($stats['rating_avg'], 1) }} ({{ $stats['rating_count'] }} reviews)
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Edit Button -->
                    @if(auth()->id() === $user->id)
                        <a href="{{ route('profile.edit') }}" 
                           class="inline-flex items-center gap-2 bg-white text-green-600 font-bold py-3 px-6 rounded-lg hover:bg-gray-100 transition shadow-lg">
                            <i class="fas fa-edit"></i>
                            <span>Edit Profile</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Rating -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Average Rating</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                {{ $stats['rating_count'] > 0 ? number_format($stats['rating_avg'], 1) : 'N/A' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">{{ $stats['rating_count'] }} reviews</p>
                        </div>
                        <div class="bg-yellow-100 rounded-full p-3">
                            <i class="fas fa-star text-2xl text-yellow-600"></i>
                        </div>
                    </div>
                </div>

                @if($user->role->value === 'freelancer')
                    <!-- Total Earned -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 font-semibold">Total Earned</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">
                                    ${{ number_format($stats['total_earned'], 0) }}
                                </p>
                            </div>
                            <div class="bg-green-100 rounded-full p-3">
                                <i class="fas fa-dollar-sign text-2xl text-green-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Gigs Completed -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 font-semibold">Gigs Completed</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['gigs_completed'] }}</p>
                            </div>
                            <div class="bg-blue-100 rounded-full p-3">
                                <i class="fas fa-check-circle text-2xl text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Total Spent -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 font-semibold">Total Spent</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">
                                    ${{ number_format($stats['total_spent'], 0) }}
                                </p>
                            </div>
                            <div class="bg-green-100 rounded-full p-3">
                                <i class="fas fa-credit-card text-2xl text-green-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Projects Posted -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 font-semibold">Projects Posted</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $user->gigs->count() }}</p>
                            </div>
                            <div class="bg-blue-100 rounded-full p-3">
                                <i class="fas fa-briefcase text-2xl text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Success Rate -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Success Rate</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">100%</p>
                        </div>
                        <div class="bg-purple-100 rounded-full p-3">
                            <i class="fas fa-chart-line text-2xl text-purple-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid lg:grid-cols-3 gap-6">
                
                <!-- Left Column - About -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- About Section -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-green-600 mr-2"></i>
                            About
                        </h3>
                        <p class="text-gray-700 whitespace-pre-line">
                            {{ $user->bio ?? 'No bio available yet.' }}
                        </p>
                    </div>

                    <!-- Contact Info (if viewing own profile) -->
                    @if(auth()->id() === $user->id)
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-envelope text-green-600 mr-2"></i>
                                Contact
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 text-sm">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                    <span class="text-gray-700">{{ $user->email }}</span>
                                </div>
                                @if($user->phone)
                                    <div class="flex items-center gap-3 text-sm">
                                        <i class="fas fa-phone text-gray-400"></i>
                                        <span class="text-gray-700">{{ $user->phone }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column - Gigs & Reviews -->
                <div class="lg:col-span-2 space-y-6">
                    
                    @if($user->role->value === 'freelancer')
                        <!-- Freelancer's Active Gigs -->
                        @if($user->gigs->count() > 0)
                            <div class="bg-white rounded-xl shadow-lg p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-briefcase text-green-600 mr-2"></i>
                                    Active Gigs ({{ $user->gigs->count() }})
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($user->gigs->take(6) as $gig)
                                        <a href="{{ route('gigs.show', $gig) }}" 
                                           class="border-2 border-gray-200 rounded-lg p-4 hover:border-green-500 hover:shadow-md transition group">
                                            <h4 class="font-semibold text-gray-900 group-hover:text-green-600 transition">
                                                {{ $gig->title }}
                                            </h4>
                                            <div class="flex items-center justify-between mt-3">
                                                <span class="text-sm font-bold text-green-600">
                                                    ${{ number_format($gig->budget, 2) }}
                                                </span>
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                    @if($gig->status->value === 'open') bg-green-100 text-green-800
                                                    @elseif($gig->status->value === 'in_progress') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $gig->status->value)) }}
                                                </span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Reviews Section -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-star text-yellow-500 mr-2"></i>
                            Reviews ({{ $stats['rating_count'] }})
                        </h3>
                        
                        @if($user->reviewsReceived->count() > 0)
                            <div class="space-y-4">
                                @foreach($user->reviewsReceived->take(5) as $review)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                        <div class="flex items-start gap-4">
                                            <!-- Reviewer Avatar -->
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                                                {{ substr($review->reviewer->name, 0, 1) }}
                                            </div>
                                            
                                            <!-- Review Content -->
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-2">
                                                    <div>
                                                        <p class="font-semibold text-gray-900">{{ $review->reviewer->name }}</p>
                                                        <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <p class="text-gray-700 text-sm">{{ $review->comment }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Show More Reviews -->
                            @if($user->reviewsReceived->count() > 5)
                                <div class="text-center mt-6">
                                    <button class="text-green-600 hover:text-green-700 font-semibold">
                                        Show All Reviews ({{ $user->reviewsReceived->count() }})
                                        <i class="fas fa-arrow-down ml-1"></i>
                                    </button>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-star text-3xl text-gray-300"></i>
                                </div>
                                <p class="text-gray-500 text-lg">No reviews yet</p>
                                <p class="text-gray-400 text-sm mt-1">Reviews will appear here after completed projects</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>