<div class="min-h-screen bg-gray-50">
    {{-- HERO SECTION --}}
    <section class="bg-gradient-to-r from-green-500 to-emerald-600 text-white py-20 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-5xl md:text-6xl font-extrabold mb-4 animate-fade-in">
                Find Your Perfect Freelancer
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90">
                <i class="fas fa-check-circle mr-2"></i>Verified Experts
                <span class="mx-3">•</span>
                <i class="fas fa-shield-alt mr-2"></i>Secure Payments
                <span class="mx-3">•</span>
                <i class="fas fa-star mr-2"></i>Quality Guaranteed
            </p>

            <!-- Search Bar -->
            <div class="max-w-3xl mx-auto">
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           placeholder="Search for gigs... (e.g. Web Development, Logo Design, Server Setup)"
                           class="w-full px-6 py-5 pl-14 rounded-2xl text-gray-900 text-lg shadow-2xl border-2 border-white focus:border-green-300 focus:outline-none transition">
                    <i class="fas fa-search absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl"></i>
                    @if($search)
                        <button wire:click="$set('search', '')" 
                                class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times-circle text-xl"></i>
                        </button>
                    @endif
                </div>

                <!-- Quick Category Pills -->
                <div class="flex flex-wrap justify-center gap-3 mt-6">
                    @php
                        $popularCategories = $categories->take(5);
                    @endphp
                    @foreach($popularCategories as $cat)
                        <button wire:click="$set('category', '{{ $cat->slug }}')"
                                class="px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full text-sm font-semibold backdrop-blur-sm transition">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            
            {{-- SIDEBAR FILTERS --}}
            <aside class="lg:w-80">
                <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-6 space-y-6">
                    <!-- Filter Header -->
                    <div class="flex items-center justify-between pb-4 border-b">
                        <h3 class="font-bold text-xl text-gray-900 flex items-center">
                            <i class="fas fa-filter text-green-600 mr-2"></i>
                            Filters
                        </h3>
                        @if($category || $search)
                            <button wire:click="clearFilters" 
                                    class="text-sm text-green-600 hover:text-green-700 font-semibold">
                                Clear All
                            </button>
                        @endif
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-folder text-gray-400 mr-2"></i>
                            Category
                        </label>
                        <select wire:model.live="category" 
                                class="w-full rounded-lg border-2 border-gray-200 focus:border-green-500 focus:outline-none py-3 px-4 text-gray-900">
                            <option value="">All Categories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->slug }}">
                                    {{ $cat->name }} ({{ $cat->gigs_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Budget Range Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-dollar-sign text-gray-400 mr-2"></i>
                            Budget Range
                        </label>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <input type="number" 
                                       wire:model.live.debounce.500ms="minBudget"
                                       placeholder="Min"
                                       class="w-full rounded-lg border-2 border-gray-200 focus:border-green-500 focus:outline-none py-2 px-3">
                                <span class="text-gray-500">to</span>
                                <input type="number" 
                                       wire:model.live.debounce.500ms="maxBudget"
                                       placeholder="Max"
                                       class="w-full rounded-lg border-2 border-gray-200 focus:border-green-500 focus:outline-none py-2 px-3">
                            </div>
                            <div class="flex gap-2 flex-wrap">
                                <button wire:click="setBudgetRange(0, 100)" 
                                        class="px-3 py-1 bg-gray-100 hover:bg-green-100 text-gray-700 hover:text-green-700 rounded-full text-xs font-semibold transition">
                                    Under $100
                                </button>
                                <button wire:click="setBudgetRange(100, 500)" 
                                        class="px-3 py-1 bg-gray-100 hover:bg-green-100 text-gray-700 hover:text-green-700 rounded-full text-xs font-semibold transition">
                                    $100 - $500
                                </button>
                                <button wire:click="setBudgetRange(500, 1000)" 
                                        class="px-3 py-1 bg-gray-100 hover:bg-green-100 text-gray-700 hover:text-green-700 rounded-full text-xs font-semibold transition">
                                    $500 - $1000
                                </button>
                                <button wire:click="setBudgetRange(1000, null)" 
                                        class="px-3 py-1 bg-gray-100 hover:bg-green-100 text-gray-700 hover:text-green-700 rounded-full text-xs font-semibold transition">
                                    $1000+
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sort Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-sort text-gray-400 mr-2"></i>
                            Sort By
                        </label>
                        <select wire:model.live="sort" 
                                class="w-full rounded-lg border-2 border-gray-200 focus:border-green-500 focus:outline-none py-3 px-4 text-gray-900">
                            <option value="latest">Latest First</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            <option value="popular">Most Popular</option>
                            <option value="applications">Most Applications</option>
                        </select>
                    </div>

                    <!-- Active Filters Summary -->
                    @if($category || $search || $minBudget || $maxBudget)
                        <div class="pt-4 border-t">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Active Filters:</p>
                            <div class="flex flex-wrap gap-2">
                                @if($category)
                                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                        {{ $categories->where('slug', $category)->first()->name ?? 'Category' }}
                                        <button wire:click="$set('category', '')" class="ml-2 hover:text-green-900">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </span>
                                @endif
                                @if($search)
                                    <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                        Search: "{{ Str::limit($search, 15) }}"
                                        <button wire:click="$set('search', '')" class="ml-2 hover:text-blue-900">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </span>
                                @endif
                                @if($minBudget || $maxBudget)
                                    <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                        ${{ $minBudget ?? 0 }} - ${{ $maxBudget ?? '∞' }}
                                        <button wire:click="clearBudget" class="ml-2 hover:text-purple-900">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </aside>

            {{-- GIGS GRID --}}
            <div class="flex-1">
                <!-- Results Header -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                @if($search)
                                    Search Results for "{{ $search }}"
                                @elseif($category)
                                    {{ $categories->where('slug', $category)->first()->name ?? 'Gigs' }}
                                @else
                                    All Gigs
                                @endif
                            </h2>
                            <p class="text-gray-600 mt-1">
                                <i class="fas fa-list-ul mr-2"></i>
                                {{ $gigs->total() }} {{ Str::plural('gig', $gigs->total()) }} found
                            </p>
                        </div>

                        <!-- View Toggle (Optional) -->
                        <div class="flex items-center gap-2">
                            <button wire:click="$set('viewMode', 'grid')" 
                                    class="p-2 rounded-lg {{ $viewMode === 'grid' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }} hover:bg-green-200 transition">
                                <i class="fas fa-th"></i>
                            </button>
                            <button wire:click="$set('viewMode', 'list')" 
                                    class="p-2 rounded-lg {{ $viewMode === 'list' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }} hover:bg-green-200 transition">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div wire:loading class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-green-600"></div>
                    <p class="text-gray-600 mt-4">Loading gigs...</p>
                </div>

                <!-- Gigs Grid/List -->
                @if ($gigs->count() === 0)
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                        <svg class="w-32 h-32 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">No Gigs Found</h3>
                        <p class="text-gray-600 mb-6">
                            @if($search || $category)
                                Try adjusting your filters or search term.
                            @else
                                There are no active gigs at the moment.
                            @endif
                        </p>
                        @if($category || $search)
                            <button wire:click="clearFilters" 
                                    class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
                                Clear Filters
                            </button>
                        @endif
                    </div>
                @else
                    <div class="grid grid-cols-1 {{ $viewMode === 'grid' ? 'md:grid-cols-2 xl:grid-cols-3' : '' }} gap-6" 
                         wire:key="gigs-grid">
                        @foreach ($gigs as $gig)
                            <x-gig-card :gig="$gig" :view-mode="$viewMode ?? 'grid'" />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($gigs->hasPages())
                        <div class="mt-8">
                            {{ $gigs->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    @guest
        <section class="bg-gradient-to-r from-green-500 to-emerald-600 text-white py-16 mt-12">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-4xl font-bold mb-4">Ready to Post Your Project?</h2>
                <p class="text-xl mb-8 opacity-90">Get started for free and find the perfect freelancer for your needs</p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('register') }}" 
                       class="px-8 py-4 bg-white text-green-600 rounded-xl font-bold hover:bg-gray-100 transition shadow-xl">
                        Sign Up Free
                    </a>
                    <a href="{{ route('login') }}" 
                       class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-xl font-bold hover:bg-white hover:text-green-600 transition">
                        Sign In
                    </a>
                </div>
            </div>
        </section>
    @endguest
</div>