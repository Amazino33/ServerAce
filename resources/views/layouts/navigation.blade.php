<nav x-data="{ open: false, categoriesOpen: false }" class="bg-white shadow-md sticky top-0 z-50 backdrop-blur-sm bg-opacity-95">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl flex items-center justify-center transform group-hover:scale-110 transition-all duration-300 shadow-lg">
                            <span class="text-white font-bold text-xl">S</span>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent hidden sm:block">
                            ServerAce
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex md:items-center md:space-x-1 md:ms-10">
                    <a href="{{ route('home') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 {{ request()->routeIs('home') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-600' }}">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>

                    <a href="{{ route('gigs.index') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 {{ request()->routeIs('browse') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-600' }}">
                        <i class="fas fa-search mr-2"></i>Browse Gigs
                    </a>

                    @php
                        $menuCat = Cache::remember('menu_categories', now()->addHours(6), function () {
                            return App\Models\Category::where('in_menu', true)
                                ->orderBy('menu_order')
                                ->orderBy('name')
                                ->get();
                        });
                    @endphp

                    <!-- Categories Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                            class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 text-gray-700 hover:bg-gray-50 hover:text-green-600 inline-flex items-center">
                            <i class="fas fa-th-large mr-2"></i>Categories
                            <svg class="ml-2 h-4 w-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute left-0 mt-2 w-64 rounded-2xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 overflow-hidden"
                             style="display: none;">
                            <div class="p-2 max-h-96 overflow-y-auto">
                                @foreach ($menuCat as $cat)
                                    <a href="{{ route('category.show', $cat->slug) }}"
                                       class="flex items-center px-4 py-3 text-sm rounded-lg transition-all duration-200 {{ request()->is('categories/' . $cat->slug) ? 'bg-green-50 text-green-700 font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-green-600' }}">
                                        <i class="fas fa-folder mr-3 text-gray-400"></i>
                                        {{ $cat->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @auth
                    <a href="{{ route('post-gig') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 {{ request()->routeIs('post-gig') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-600' }}">
                        <i class="fas fa-plus-circle mr-2"></i>Post a Gig
                    </a>

                    <a href="{{ route('dashboard') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50 hover:text-green-600' }}">
                        <i class="fas fa-chart-line mr-2"></i>Dashboard
                    </a>
                    @endauth
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center">
                @auth
                <!-- User Dropdown -->
                <div class="hidden md:flex md:items-center md:space-x-3">
                    <x-role-badge />
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                            class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center text-white font-bold shadow-md">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="text-sm font-semibold text-gray-700 hidden lg:block">{{ Str::limit(Auth::user()->name, 15) }}</span>
                            <svg class="h-4 w-4 text-gray-500 transition-transform duration-200" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>

                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 rounded-2xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 overflow-hidden"
                             style="display: none;">
                            <div class="p-2">
                                <a href="{{ route('profile.edit') }}"
                                   class="flex items-center px-4 py-3 text-sm rounded-lg text-gray-700 hover:bg-gray-50 hover:text-green-600 transition-all duration-200">
                                    <i class="fas fa-user-circle mr-3 text-gray-400"></i>
                                    Profile
                                </a>
                                
                                <hr class="my-2 border-gray-100">
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center px-4 py-3 text-sm rounded-lg text-red-600 hover:bg-red-50 transition-all duration-200 font-semibold">
                                        <i class="fas fa-sign-out-alt mr-3"></i>
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- Guest Buttons -->
                <div class="hidden md:flex items-center space-x-3">
                    <a href="{{ route('login') }}" 
                       class="px-5 py-2.5 text-sm font-semibold text-gray-700 hover:text-green-600 transition-all duration-200">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" 
                       class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-blue-600 text-white font-bold rounded-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 shadow-md">
                        Start Free <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                @endauth

                <!-- Mobile menu button -->
                <button @click="open = !open" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-600 hover:text-green-600 hover:bg-gray-50 transition-all duration-200 ml-3">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path :class="{'hidden': open, 'inline-flex': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="md:hidden border-t border-gray-100 bg-white shadow-lg"
         style="display: none;">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('home') }}" 
               class="flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('home') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-home mr-3 w-5"></i>Home
            </a>

            <a href="{{ route('gigs.index') }}" 
               class="flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('browse') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-search mr-3 w-5"></i>Browse Gigs
            </a>

            <!-- Mobile Categories -->
            <div x-data="{ catOpen: false }">
                <button @click="catOpen = !catOpen"
                        class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-200">
                    <span><i class="fas fa-th-large mr-3 w-5"></i>Categories</span>
                    <svg class="h-4 w-4 transition-transform duration-200" :class="{'rotate-180': catOpen}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
                
                <div x-show="catOpen" 
                     x-transition
                     class="ml-4 mt-1 space-y-1"
                     style="display: none;">
                    @foreach ($menuCat as $cat)
                        <a href="{{ route('category.show', $cat->slug) }}"
                           class="flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->is('categories/' . $cat->slug) ? 'bg-green-50 text-green-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                            <i class="fas fa-folder mr-3 text-xs"></i>
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            @auth
            <a href="{{ route('post-gig') }}" 
               class="flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('post-gig') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-plus-circle mr-3 w-5"></i>Post a Gig
            </a>

            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-chart-line mr-3 w-5"></i>Dashboard
            </a>
            @endauth
        </div>

        @auth
        <!-- Mobile User Section -->
        <div class="px-4 py-4 border-t border-gray-100 bg-gray-50">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center text-white font-bold shadow-md">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <x-role-badge />
            </div>

            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center px-4 py-2 text-sm font-semibold rounded-lg text-gray-700 hover:bg-white transition-all duration-200">
                    <i class="fas fa-user-circle mr-3"></i>Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center px-4 py-2 text-sm font-semibold rounded-lg text-red-600 hover:bg-white transition-all duration-200">
                        <i class="fas fa-sign-out-alt mr-3"></i>Log Out
                    </button>
                </form>
            </div>
        </div>
        @else
        <!-- Mobile Guest Section -->
        <div class="px-4 py-4 border-t border-gray-100 bg-gray-50 space-y-2">
            <a href="{{ route('login') }}" 
               class="block w-full text-center px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white rounded-lg hover:bg-gray-100 transition-all duration-200">
                Log in
            </a>
            <a href="{{ route('register') }}" 
               class="block w-full text-center px-6 py-2.5 bg-gradient-to-r from-green-600 to-blue-600 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                Start Free <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        @endauth
    </div>
</nav>