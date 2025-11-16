<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="text-gray-600 mt-1">Manage platform, users, and gigs</p>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Total Users</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_users'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $stats['total_clients'] }} clients, {{ $stats['total_freelancers'] }} freelancers
                        </p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Gigs -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Total Gigs</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_gigs'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $stats['open_gigs'] }} open, {{ $stats['completed_gigs'] }} completed
                        </p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Approvals -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Pending Approval</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_gigs'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $stats['approved_gigs'] }} approved
                        </p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Applications -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Applications</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_applications'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $stats['pending_applications'] }} pending, {{ $stats['accepted_applications'] }} accepted
                        </p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
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
                            class="px-6 py-4 text-sm font-medium border-b-2 transition {{ $activeTab === 'overview' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-chart-line mr-2"></i> Overview
                    </button>
                    <button wire:click="setActiveTab('gigs')"
                            class="px-6 py-4 text-sm font-medium border-b-2 transition {{ $activeTab === 'gigs' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-briefcase mr-2"></i> Gigs ({{ $stats['total_gigs'] }})
                    </button>
                    <button wire:click="setActiveTab('users')"
                            class="px-6 py-4 text-sm font-medium border-b-2 transition {{ $activeTab === 'users' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-users mr-2"></i> Users ({{ $stats['total_users'] }})
                    </button>
                    <button wire:click="setActiveTab('applications')"
                            class="px-6 py-4 text-sm font-medium border-b-2 transition {{ $activeTab === 'applications' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-inbox mr-2"></i> Applications ({{ $stats['total_applications'] }})
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        <div>
            <!-- OVERVIEW TAB -->
            @if($activeTab === 'overview')
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <button wire:click="setActiveTab('gigs'); setGigStatusFilter('pending')"
                                    class="p-4 border-2 border-yellow-200 rounded-lg hover:border-yellow-400 transition text-left">
                                <div class="flex items-center gap-3">
                                    <div class="bg-yellow-100 rounded-full p-2">
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Review Pending Gigs</p>
                                        <p class="text-sm text-gray-600">{{ $stats['pending_gigs'] }} waiting</p>
                                    </div>
                                </div>
                            </button>

                            <button wire:click="setActiveTab('users')"
                                    class="p-4 border-2 border-purple-200 rounded-lg hover:border-purple-400 transition text-left">
                                <div class="flex items-center gap-3">
                                    <div class="bg-purple-100 rounded-full p-2">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Manage Users</p>
                                        <p class="text-sm text-gray-600">{{ $stats['total_users'] }} total</p>
                                    </div>
                                </div>
                            </button>

                            <button wire:click="setActiveTab('applications')"
                                    class="p-4 border-2 border-green-200 rounded-lg hover:border-green-400 transition text-left">
                                <div class="flex items-center gap-3">
                                    <div class="bg-green-100 rounded-full p-2">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">View Applications</p>
                                        <p class="text-sm text-gray-600">{{ $stats['pending_applications'] }} pending</p>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Recent Activity</h3>
                        <div class="space-y-4">
                            @foreach($recentActivity as $activity)
                                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    @if($activity['type'] === 'gig')
                                        <div class="bg-blue-100 rounded-full p-2 mt-1">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900">New Gig Posted</p>
                                            <p class="text-sm text-gray-600">{{ $activity['data']->title }}</p>
                                            <p class="text-xs text-gray-500 mt-1">by {{ $activity['data']->client->name }} • {{ $activity['created_at']->diffForHumans() }}</p>
                                        </div>
                                        <button wire:click="viewGigDetail({{ $activity['data']->id }})" 
                                                class="text-indigo-600 hover:text-indigo-700 font-semibold text-sm">
                                            View →
                                        </button>
                                    @elseif($activity['type'] === 'user')
                                        <div class="bg-purple-100 rounded-full p-2 mt-1">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900">New User Registered</p>
                                            <p class="text-sm text-gray-600">{{ $activity['data']->name }} ({{ ucfirst($activity['data']->role->value) }})</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $activity['created_at']->diffForHumans() }}</p>
                                        </div>
                                        <button wire:click="viewUserDetail({{ $activity['data']->id }})" 
                                                class="text-indigo-600 hover:text-indigo-700 font-semibold text-sm">
                                            View →
                                        </button>
                                    @else
                                        <div class="bg-green-100 rounded-full p-2 mt-1">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900">New Application</p>
                                            <p class="text-sm text-gray-600">{{ $activity['data']->freelancer->name }} applied to "{{ $activity['data']->gig->title }}"</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $activity['created_at']->diffForHumans() }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- GIGS TAB -->
            @if($activeTab === 'gigs')
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <!-- Filters -->
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Manage Gigs</h3>
                        <div class="flex gap-2">
                            <button wire:click="setGigStatusFilter('all')"
                                    class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ $gigStatusFilter === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                All
                            </button>
                            <button wire:click="setGigStatusFilter('pending')"
                                    class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ $gigStatusFilter === 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                Pending ({{ $stats['pending_gigs'] }})
                            </button>
                            <button wire:click="setGigStatusFilter('approved')"
                                    class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ $gigStatusFilter === 'approved' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                Approved
                            </button>
                            <button wire:click="setGigStatusFilter('open')"
                                    class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ $gigStatusFilter === 'open' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                Open
                            </button>
                            <button wire:click="setGigStatusFilter('closed')"
                                    class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ $gigStatusFilter === 'closed' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                Closed
                            </button>
                        </div>
                    </div>

                    <!-- Gigs List -->
                    @if($gigs->count() > 0)
                        <div class="space-y-4">
                            @foreach($gigs as $gig)
                                <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h4 class="text-lg font-bold text-gray-900">{{ $gig->title }}</h4>
                                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                                    {{ $gig->category->name ?? 'Uncategorized' }}
                                                </span>
                                                @if(!$gig->is_approved)
                                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                                        Pending Approval
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <p class="text-gray-600 mb-3">{{ Str::limit($gig->description, 120) }}</p>
                                            
                                            <div class="flex items-center gap-6 text-sm text-gray-500">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    <span>{{ $gig->client->name }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="font-semibold">{{ $gig->budget_display }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    <span>{{ $gig->applications->count() }} applications</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>{{ $gig->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <button wire:click="viewGigDetail({{ $gig->id }})"
                                                class="ml-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition">
                                            Manage
                                        </button>
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
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <p class="text-gray-500 text-lg">No gigs found</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- USERS TAB -->
            @if($activeTab === 'users')
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <!-- Filters -->
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Manage Users</h3>
                        <div class="flex gap-2">
                            <button wire:click="setUserRoleFilter('all')"
                                    class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ $userRoleFilter === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                All
                            </button>
                            <button wire:click="setUserRoleFilter('client')"
                                    class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ $userRoleFilter === 'client' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                Clients ({{ $stats['total_clients'] }})
                            </button>
                            <button wire:click="setUserRoleFilter('freelancer')"
                                    class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ $userRoleFilter === 'freelancer' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                Freelancers ({{ $stats['total_freelancers'] }})
                            </button>
                        </div>
                    </div>

                    <!-- Users List -->
                    @if($users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($users as $user)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-bold">
                                                        {{ substr($user->name, 0, 1) }}
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $user->role->value === 'client' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                    {{ ucfirst($user->role->value) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($user->role->value === 'client')
                                                    {{ $user->posted_gigs_count }} gigs posted
                                                @else
                                                    {{ $user->gig_applications_count }} applications
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $user->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button wire:click="viewUserDetail({{ $user->id }})"
                                                        class="text-indigo-600 hover:text-indigo-900">
                                                    View Details
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="text-gray-500 text-lg">No users found</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- APPLICATIONS TAB -->
            @if($activeTab === 'applications')
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">All Applications</h3>
                    
                    @if($applications->count() > 0)
                        <div class="space-y-4">
                            @foreach($applications as $application)
                                <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h4 class="text-lg font-bold text-gray-900">{{ $application->gig->title }}</h4>
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                    @if($application->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($application->status === 'accepted') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                            </div>
                                            
                                            <div class="grid grid-cols-2 gap-4 mb-3">
                                                <div>
                                                    <p class="text-sm text-gray-500">Freelancer</p>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $application->freelancer->name }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Client</p>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $application->gig->client->name }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Proposed Price</p>
                                                    <p class="text-sm font-semibold text-green-600">${{ number_format($application->proposed_price, 2) }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Applied</p>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $application->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>

                                            <p class="text-sm text-gray-600 line-clamp-2">{{ $application->cover_letter }}</p>
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
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500 text-lg">No applications yet</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- GIG DETAIL MODAL -->
        @if($showGigDetailModal && $selectedGig)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-4 max-h-screen overflow-y-auto">
                    
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4 rounded-t-2xl flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-white">Gig Details</h3>
                        <button wire:click="closeGigDetailModal" class="text-white hover:text-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-8">
                        <!-- Approval Status -->
                        <div class="mb-6 p-4 rounded-lg {{ $selectedGig->is_approved ? 'bg-green-50' : 'bg-yellow-50' }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold {{ $selectedGig->is_approved ? 'text-green-800' : 'text-yellow-800' }}">
                                        Approval Status: {{ $selectedGig->is_approved ? 'Approved' : 'Pending Approval' }}
                                    </p>
                                    @if($selectedGig->is_approved && $selectedGig->approved_at)
                                        <p class="text-sm text-gray-600 mt-1">
                                            Approved {{ $selectedGig->approved_at->diffForHumans() }}
                                        </p>
                                    @endif
                                </div>
                                @if(!$selectedGig->is_approved)
                                    <button wire:click="approveGig({{ $selectedGig->id }})"
                                            wire:confirm="Are you sure you want to approve this gig?"
                                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
                                        Approve Now
                                    </button>
                                @else
                                    <button wire:click="rejectGig({{ $selectedGig->id }})"
                                            wire:confirm="Are you sure you want to unapprove this gig?"
                                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition">
                                        Unapprove
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Gig Info -->
                        <div class="mb-6">
                            <h4 class="text-2xl font-bold text-gray-900 mb-2">{{ $selectedGig->title }}</h4>
                            <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-semibold">
                                    {{ $selectedGig->category->name ?? 'Uncategorized' }}
                                </span>
                                <span>Posted {{ $selectedGig->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700 whitespace-pre-line">{{ $selectedGig->description }}</p>
                        </div>

                        <!-- Budget -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-2">Budget</h4>
                            <p class="text-2xl font-bold text-green-600">{{ $selectedGig->budget_display }}</p>
                        </div>

                        <!-- Client Info -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-2">Client</h4>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xl font-bold">
                                    {{ substr($selectedGig->client->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $selectedGig->client->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $selectedGig->client->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Applications -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">
                                Applications ({{ $selectedGig->applications->count() }})
                            </h4>
                            @if($selectedGig->applications->count() > 0)
                                <div class="space-y-3 max-h-64 overflow-y-auto">
                                    @foreach($selectedGig->applications as $app)
                                        <div class="p-4 border border-gray-200 rounded-lg">
                                            <div class="flex items-center justify-between mb-2">
                                                <p class="font-semibold text-gray-900">{{ $app->freelancer->name }}</p>
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                    @if($app->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($app->status === 'accepted') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($app->status) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600">
                                                Proposed: <span class="font-semibold text-green-600">${{ number_format($app->proposed_price, 2) }}</span>
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No applications yet</p>
                            @endif
                        </div>

                        <!-- Awarded Freelancer -->
                        @if($selectedGig->freelancer)
                            <div class="mb-6 p-4 bg-green-50 rounded-lg">
                                <h4 class="font-semibold text-green-800 mb-2">Awarded To</h4>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center text-white font-bold">
                                        {{ substr($selectedGig->freelancer->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $selectedGig->freelancer->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $selectedGig->freelancer->email }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex gap-4 pt-4 border-t">
                            <a href="{{ route('gigs.show', $selectedGig->slug) }}" 
                               target="_blank"
                               class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl transition text-center">
                                View Public Page →
                            </a>
                            <button wire:click="deleteGig({{ $selectedGig->id }})"
                                    wire:confirm="Are you sure you want to delete this gig? This action cannot be undone."
                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl transition">
                                Delete Gig
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- USER DETAIL MODAL -->
        @if($showUserDetailModal && $selectedUser)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full mx-4 max-h-screen overflow-y-auto">
                    
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 px-6 py-4 rounded-t-2xl flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-white">User Details</h3>
                        <button wire:click="closeUserDetailModal" class="text-white hover:text-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-8">
                        <!-- User Info -->
                        <div class="mb-6 text-center">
                            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-purple-400 to-indigo-500 text-white text-4xl font-bold mb-4">
                                {{ substr($selectedUser->name, 0, 1) }}
                            </div>
                            <h4 class="text-2xl font-bold text-gray-900">{{ $selectedUser->name }}</h4>
                            <p class="text-gray-600">{{ $selectedUser->email }}</p>
                            <span class="inline-block mt-2 px-4 py-2 rounded-full text-sm font-semibold
                                {{ $selectedUser->role->value === 'client' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ ucfirst($selectedUser->role->value) }}
                            </span>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <p class="text-2xl font-bold text-gray-900">{{ $selectedUser->postedGigs->count() }}</p>
                                <p class="text-sm text-gray-600">Gigs Posted</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <p class="text-2xl font-bold text-gray-900">{{ $selectedUser->gigApplications->count() }}</p>
                                <p class="text-sm text-gray-600">Applications</p>
                            </div>
                        </div>

                        <!-- Registration Date -->
                        <div class="mb-6 text-center text-sm text-gray-500">
                            Joined {{ $selectedUser->created_at->diffForHumans() }}
                            ({{ $selectedUser->created_at->format('M d, Y') }})
                        </div>

                        <!-- Recent Activity -->
                        @if($selectedUser->role->value === 'client' && $selectedUser->postedGigs->count() > 0)
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-700 mb-3">Recent Gigs Posted</h4>
                                <div class="space-y-2 max-h-48 overflow-y-auto">
                                    @foreach($selectedUser->postedGigs->take(5) as $gig)
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="font-semibold text-gray-900 text-sm">{{ $gig->title }}</p>
                                            <p class="text-xs text-gray-500">{{ $gig->created_at->diffForHumans() }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($selectedUser->role->value === 'freelancer' && $selectedUser->gigApplications->count() > 0)
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-700 mb-3">Recent Applications</h4>
                                <div class="space-y-2 max-h-48 overflow-y-auto">
                                    @foreach($selectedUser->gigApplications->take(5) as $app)
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="font-semibold text-gray-900 text-sm">{{ $app->gig->title }}</p>
                                            <div class="flex items-center justify-between mt-1">
                                                <span class="text-xs px-2 py-1 rounded-full
                                                    @if($app->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($app->status === 'accepted') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($app->status) }}
                                                </span>
                                                <p class="text-xs text-gray-500">{{ $app->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex gap-4 pt-4 border-t">
                            <button wire:click="deleteUser({{ $selectedUser->id }})"
                                    wire:confirm="Are you sure you want to delete this user? All their gigs and applications will be deleted. This action cannot be undone."
                                    class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl transition">
                                Delete User
                            </button>
                            <button wire:click="closeUserDetailModal"
                                    class="flex-1 border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-3 px-6 rounded-xl transition">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>