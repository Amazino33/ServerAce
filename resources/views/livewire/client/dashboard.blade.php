<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Client Dashboard</h1>
            <p class="text-gray-600 mt-1">Post gigs and manage applications</p>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                <p class="font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <!-- Total Gigs -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Total Gigs</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_gigs'] }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Gigs -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Active Gigs</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_gigs'] }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Applications -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Total Applications</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_applications'] ?? 0 }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Applications -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Pending</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_applications'] }}</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Accepted Applications -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Hired</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['accepted_applications'] }}</p>
                    </div>
                    <div class="bg-indigo-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="bg-white rounded-xl shadow-lg mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button wire:click="setActiveTab('overview')"
                        class="px-6 py-4 text-sm font-medium border-b-2 transition {{ $activeTab === 'overview' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-chart-line mr-2"></i> Overview
                    </button>
                    <button wire:click="setActiveTab('gigs')"
                        class="px-6 py-4 text-sm font-medium border-b-2 transition {{ $activeTab === 'gigs' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-briefcase mr-2"></i> My Gigs ({{ $stats['total_gigs'] }})
                    </button>
                    <button wire:click="setActiveTab('applications')"
                        class="px-6 py-4 text-sm font-medium border-b-2 transition {{ $activeTab === 'applications' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-inbox mr-2"></i> Applications ({{ $stats['pending_applications'] }})
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        <div>
            <!-- OVERVIEW TAB -->
            @if($activeTab === 'overview')
                <div class="space-y-6">
                    <!-- Welcome Card -->
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl shadow-lg p-8 text-white">
                        <h2 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h2>
                        <p class="text-green-100 mb-4">Manage your gigs and find the perfect freelancer for your projects
                        </p>
                        <button wire:click="$dispatch('openModal')"
                            class="inline-block bg-white text-green-600 font-bold py-3 px-6 rounded-lg hover:bg-green-50 transition">
                            <i class="fas fa-plus-circle mr-2"></i>Post a New Gig
                        </button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Recent Gigs -->
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900">Recent Gigs</h3>
                                <button wire:click="setActiveTab('gigs')"
                                    class="text-green-600 hover:text-green-700 font-semibold text-sm">
                                    View All →
                                </button>
                            </div>

                            @if($recentGigs && $recentGigs->count() > 0)
                                <div class="space-y-4">
                                    @foreach($recentGigs as $gig)
                                        <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <p class="font-semibold text-gray-900">{{ $gig->title }}</p>
                                                    <div class="flex items-center gap-3 mt-2 text-sm text-gray-600">
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                </path>
                                                            </svg>
                                                            ${{ number_format($gig->budget, 2) }}
                                                        </span>
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                                </path>
                                                            </svg>
                                                            {{ $gig->applications_count }} applicants
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                                @if($gig->status->value === 'open') bg-green-100 text-green-800
                                                                @elseif($gig->status->value === 'closed') bg-gray-100 text-gray-800
                                                                @else bg-blue-100 text-blue-800
                                                                @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $gig->status->value)) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p class="text-gray-500">No gigs yet</p>
                                    <a href="{{ route('post-gig') }}"
                                        class="mt-3 inline-block text-green-600 hover:text-green-700 font-semibold text-sm">
                                        Post Your First Gig →
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Recent Applications -->
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900">Recent Applications</h3>
                                <button wire:click="setActiveTab('applications')"
                                    class="text-green-600 hover:text-green-700 font-semibold text-sm">
                                    View All →
                                </button>
                            </div>

                            @if($recentApplications && $recentApplications->count() > 0)
                                <div class="space-y-4">
                                    @foreach($recentApplications as $application)
                                        <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3 flex-1">
                                                    <div
                                                        class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                                                        {{ substr($application->freelancer->name, 0, 1) }}
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="font-semibold text-gray-900 truncate">
                                                            {{ $application->freelancer->name }}</p>
                                                        <p class="text-sm text-gray-600 truncate">{{ $application->gig->title }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                                    @if($application->status === 'pending') bg-yellow-100 text-yellow-800
                                                                    @elseif($application->status === 'accepted') bg-green-100 text-green-800
                                                                    @else bg-red-100 text-red-800
                                                                    @endif">
                                                        {{ ucfirst($application->status) }}
                                                    </span>
                                                    @if($application->status === 'pending')
                                                        <button wire:click="viewApplication({{ $application->id }})"
                                                            class="text-green-600 hover:text-green-700 font-semibold text-sm">
                                                            Review →
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-gray-500">No applications yet</p>
                                    <p class="text-gray-400 text-sm mt-1">Applications will appear here</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <button wire:click="$dispatch('openModal')"
                                class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition group">
                                <div
                                    class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Post New Gig</p>
                                    <p class="text-sm text-gray-600">Create a new project</p>
                                </div>
                            </button>

                            <button wire:click="setActiveTab('applications')"
                                class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-lg hover:border-yellow-500 hover:bg-yellow-50 transition group">
                                <div
                                    class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 transition">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Review Applications</p>
                                    <p class="text-sm text-gray-600">{{ $stats['pending_applications'] }} pending</p>
                                </div>
                            </button>

                            <button wire:click="setActiveTab('gigs')"
                                class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition group">
                                <div
                                    class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Manage Gigs</p>
                                    <p class="text-sm text-gray-600">{{ $stats['active_gigs'] }} active gigs</p>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Activity Stats -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Activity Overview</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_gigs'] }}</p>
                                <p class="text-sm text-gray-600 mt-1">Total Gigs Posted</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <p class="text-2xl font-bold text-green-600">{{ $stats['active_gigs'] }}</p>
                                <p class="text-sm text-gray-600 mt-1">Currently Active</p>
                            </div>
                            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                                <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_applications'] }}</p>
                                <p class="text-sm text-gray-600 mt-1">Awaiting Review</p>
                            </div>
                            <div class="text-center p-4 bg-indigo-50 rounded-lg">
                                <p class="text-2xl font-bold text-indigo-600">{{ $stats['accepted_applications'] }}</p>
                                <p class="text-sm text-gray-600 mt-1">Freelancers Hired</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- GIGS TAB -->
            @if($activeTab === 'gigs')
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <!-- Filters -->
                    <div class="flex flex-col md:flex-row gap-4 mb-6">
                        <div class="flex-1 relative">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" wire:model.live="searchTerm"
                                placeholder="Search gigs by title or description..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div class="relative">
                            <select wire:model.live="filterStatus"
                                class="pl-4 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 appearance-none bg-white cursor-pointer min-w-[200px]">
                                <option value="all">All Status</option>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                                <option value="in_progress">In Progress</option>
                            </select>
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 mb-6">My Gigs</h3>

                    @if($gigs->count() > 0)
                        <div class="space-y-4">
                            @foreach($gigs as $gig)
                                <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h4 class="text-xl font-bold text-gray-900">{{ $gig->title }}</h4>
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                                @if($gig->status->value === 'open') bg-green-100 text-green-800
                                                                @elseif($gig->status->value === 'closed') bg-gray-100 text-gray-800
                                                                @else bg-blue-100 text-blue-800
                                                                @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $gig->status->value)) }}
                                                </span>
                                            </div>

                                            <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($gig->description, 150) }}</p>

                                            <div class="flex items-center gap-6 text-sm text-gray-500">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                    <span class="font-semibold">${{ number_format($gig->budget, 2) }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                        </path>
                                                    </svg>
                                                    <span>{{ $gig->applications_count }}
                                                        {{ Str::plural('application', $gig->applications_count) }}</span>
                                                </div>
                                                @if($gig->pending_applications_count > 0)
                                                    <div class="flex items-center gap-2 text-yellow-600 font-semibold">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <span>{{ $gig->pending_applications_count }} pending</span>
                                                    </div>
                                                @endif
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    <span>{{ $gig->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ml-6 flex flex-col gap-2">
                                            <button wire:click="toggleGigStatus({{ $gig->id }})"
                                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition text-sm">
                                                <i
                                                    class="fas fa-{{ $gig->status->value === 'open' ? 'lock' : 'lock-open' }} mr-1"></i>
                                                {{ $gig->status->value === 'open' ? 'Close' : 'Reopen' }}
                                            </button>

                                            <button wire:click="openInHouseModal({{ $gig->id }})"
                                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition text-sm">
                                                <i class="fas fa-home mr-1"></i>
                                                In-House
                                            </button>

                                            <button wire:click="deleteGig({{ $gig->id }})"
                                                wire:confirm="Are you sure you want to delete this gig?"
                                                class="px-4 py-2 border border-red-300 text-red-600 rounded-lg font-semibold hover:bg-red-50 transition text-sm">
                                                <i class="fas fa-trash mr-1"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $gigs->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="text-gray-500 text-lg">No gigs found</p>
                            <p class="text-gray-400 mt-1">Get started by posting your first gig</p>
                            <button wire:click="$dispatch('openModal')"
                                    class="inline-block bg-white text-green-600 font-bold py-3 px-6 rounded-lg hover:bg-green-50 transition">
                                <i class="fas fa-plus-circle mr-2"></i>Post a New Gig
                            </button>
                        </div>
                    @endif
                </div>
            @endif

            <!-- APPLICATIONS TAB -->
            @if($activeTab === 'applications')
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <!-- Filter -->
                    <div class="mb-6">
                        <div class="relative">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                </path>
                            </svg>
                            <select wire:model.live="filterGigForApplications"
                                class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 appearance-none bg-white cursor-pointer">
                                <option value="all">All Gigs</option>
                                @foreach($gigsForFilter as $gig)
                                    <option value="{{ $gig->id }}">{{ $gig->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 mb-6">Applications</h3>

                    @if($applications->count() > 0)
                        <div class="space-y-4">
                            @foreach($applications as $application)
                                <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start gap-4 flex-1">
                                            <!-- Freelancer Avatar -->
                                            <div
                                                class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                                                {{ substr($application->freelancer->name, 0, 1) }}
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-lg font-bold text-gray-900 mb-1">
                                                    {{ $application->freelancer->name }}</h4>
                                                <p class="text-sm text-gray-600 mb-2">
                                                    Applied to: <span class="font-semibold">{{ $application->gig->title }}</span>
                                                </p>
                                                <p class="text-sm text-gray-700 mb-3 line-clamp-2 bg-gray-50 p-3 rounded-lg">
                                                    {{ Str::limit($application->cover_letter, 200) }}
                                                </p>
                                                <div class="flex items-center gap-4">
                                                    <span class="flex items-center text-sm font-semibold text-green-600">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                            </path>
                                                        </svg>
                                                        ${{ number_format($application->proposed_rate, 2) }}
                                                    </span>
                                                    <span class="flex items-center text-xs text-gray-500">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ $application->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ml-6 flex flex-col items-end gap-3">
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                                            @if($application->status === 'pending') bg-yellow-100 text-yellow-800
                                                            @elseif($application->status === 'accepted') bg-green-100 text-green-800
                                                            @else bg-red-100 text-red-800
                                                            @endif">
                                                {{ ucfirst($application->status) }}
                                            </span>

                                            @if($application->status === 'pending')
                                                <button wire:click="viewApplication({{ $application->id }})"
                                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition">
                                                    Review
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $applications->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <p class="text-gray-500 text-lg">No applications yet</p>
                            <p class="text-gray-400 mt-1">Applications will appear here when freelancers apply</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Application Details Modal -->
        @if($showApplicationModal && $selectedApplication)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full mx-4 max-h-screen overflow-y-auto">

                    <!-- Modal Header -->
                    <div
                        class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 rounded-t-2xl flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-white">Application Details</h3>
                        <button wire:click="closeApplicationModal" class="text-white hover:text-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-8">
                        <!-- Freelancer Info -->
                        <div class="bg-gray-50 rounded-xl p-6 mb-6">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-16 h-16 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center text-white font-bold text-2xl">
                                    {{ substr($selectedApplication->freelancer->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-xl font-bold text-gray-900 mb-1">
                                        {{ $selectedApplication->freelancer->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $selectedApplication->freelancer->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Gig Info -->
                        <div class="bg-blue-50 rounded-xl p-4 mb-6">
                            <h4 class="font-semibold text-gray-700 mb-2">Gig:</h4>
                            <p class="text-lg font-bold text-gray-900">{{ $selectedApplication->gig->title }}</p>
                            <p class="text-sm text-gray-600 mt-2">
                                Proposed Rate: <span
                                    class="font-bold text-green-600">${{ number_format($selectedApplication->proposed_rate, 2) }}</span>
                            </p>
                        </div>

                        <!-- Cover Letter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-2">Cover Letter</h4>
                            <div class="bg-white border border-gray-200 rounded-xl p-4 text-gray-700 whitespace-pre-line">
                                {{ $selectedApplication->cover_letter }}
                            </div>
                        </div>

                        <!-- Application Date -->
                        <div class="mb-6 text-sm text-gray-500">
                            Submitted {{ $selectedApplication->created_at->diffForHumans() }}
                            ({{ $selectedApplication->created_at->format('M d, Y \a\t h:i A') }})
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-8 py-6 rounded-b-2xl flex justify-end gap-4">
                        <button wire:click="closeApplicationModal"
                            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-100 transition">
                            Close
                        </button>
                        <button wire:click="rejectApplication({{ $selectedApplication->id }})"
                            wire:confirm="Are you sure you want to reject this application?"
                            class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold transition">
                            <i class="fas fa-times mr-2"></i>Reject
                        </button>
                        <button wire:click="acceptApplication({{ $selectedApplication->id }})"
                            wire:confirm="This will reject all other applications for this gig. Continue?"
                            class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold transition">
                            <i class="fas fa-check mr-2"></i>Accept & Hire
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- In-House Assignment Modal -->
        @if($selectedGigId)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4">

                    <!-- Modal Header -->
                    <div
                        class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4 rounded-t-2xl flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-white">
                            <i class="fas fa-home mr-2"></i>Assign to In-House Developer
                        </h3>
                        <button wire:click="$set('selectedGigId', null)" class="text-white hover:text-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-8">
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                            <p class="text-sm text-gray-700">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Submit a request to assign this gig to an in-house developer. Our team will review and
                                assign a qualified developer from our internal team.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Additional Notes <span class="text-gray-500">(Optional)</span>
                            </label>
                            <textarea wire:model="inhouseNotes" rows="6"
                                placeholder="Add any specific requirements, preferences, or timeline expectations..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"></textarea>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-8 py-6 rounded-b-2xl flex justify-end gap-4">
                        <button wire:click="$set('selectedGigId', null)"
                            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-100 transition">
                            Cancel
                        </button>
                        <button wire:click="assignToInHouseDeveloper"
                            class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-bold transition">
                            <i class="fas fa-paper-plane mr-2"></i>Submit Request
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <livewire:client.create-gig />
</div>
