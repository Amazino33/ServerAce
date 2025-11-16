<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Freelancer Dashboard</h1>
            <p class="text-gray-600 mt-1">Find gigs and manage your applications</p>
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
            <!-- Total Applications -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Total Applications</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_applications'] }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Accepted Applications -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Accepted</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['accepted_applications'] }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Rejected Applications -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Rejected</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['rejected_applications'] }}</p>
                    </div>
                    <div class="bg-red-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Projects -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Active Projects</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_projects'] }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
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
                    <button wire:click="setActiveTab('browse')"
                            class="px-6 py-4 text-sm font-medium border-b-2 transition {{ $activeTab === 'browse' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-search mr-2"></i> Browse Gigs
                    </button>
                    <button wire:click="setActiveTab('applications')"
                            class="px-6 py-4 text-sm font-medium border-b-2 transition {{ $activeTab === 'applications' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-inbox mr-2"></i> My Applications ({{ $stats['total_applications'] }})
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
                        <p class="text-green-100">Ready to find your next project?</p>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Recent Activity</h3>
                        
                        @if($recentActivity->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentActivity as $application)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900">{{ $application->gig->title }}</p>
                                            <p class="text-sm text-gray-600">Applied {{ $application->created_at->diffForHumans() }}</p>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                                @if($application->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($application->status === 'accepted') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                            <button wire:click="viewApplicationDetail({{ $application->id }})" 
                                                    class="text-green-600 hover:text-green-700 font-semibold">
                                                View →
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-500 text-lg">No applications yet</p>
                                <p class="text-gray-400 mt-1">Start browsing gigs to submit your first application!</p>
                                <button wire:click="setActiveTab('browse')" 
                                        class="mt-4 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition">
                                    Browse Gigs
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- BROWSE GIGS TAB -->
            @if($activeTab === 'browse')
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Available Gigs</h3>
                    
                    @if($availableGigs->count() > 0)
                        <div class="space-y-6">
                            @foreach($availableGigs as $gig)
                                <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h4 class="text-xl font-bold text-gray-900">{{ $gig->title }}</h4>
                                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                                    {{ $gig->category->name ?? 'Uncategorized' }}
                                                </span>
                                            </div>
                                            
                                            <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($gig->description, 150) }}</p>
                                            
                                            <div class="flex items-center gap-6 text-sm text-gray-500">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    <span>{{ $gig->client->name }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>Posted {{ $gig->created_at->diffForHumans() }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    <span>{{ $gig->pending_count }} applicants</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="ml-6 text-right">
                                            <p class="text-2xl font-bold text-green-600 mb-3">{{ $gig->budget_display }}</p>
                                            <button wire:click="openApplicationModal({{ $gig->id }})"
                                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition">
                                                Apply Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $availableGigs->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <p class="text-gray-500 text-lg">No available gigs at the moment</p>
                            <p class="text-gray-400 mt-1">Check back later for new opportunities!</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- MY APPLICATIONS TAB -->
            @if($activeTab === 'applications')
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">My Applications</h3>
                    
                    @if($myApplications->count() > 0)
                        <div class="space-y-4">
                            @foreach($myApplications as $application)
                                <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-bold text-gray-900 mb-2">{{ $application->gig->title }}</h4>
                                            <p class="text-sm text-gray-600 mb-3">Client: {{ $application->gig->client->name }}</p>
                                            
                                            <div class="flex items-center gap-6 text-sm text-gray-500 mb-3">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="font-semibold">${{ number_format($application->proposed_price, 2) }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>{{ $application->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>

                                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                                @if($application->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($application->status === 'accepted') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                        </div>
                                        
                                        <button wire:click="viewApplicationDetail({{ $application->id }})"
                                                class="ml-6 text-green-600 hover:text-green-700 font-semibold">
                                            View Details →
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $myApplications->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500 text-lg">No applications yet</p>
                            <p class="text-gray-400 mt-1">Browse available gigs and submit your first application!</p>
                            <button wire:click="setActiveTab('browse')" 
                                    class="mt-4 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition">
                                Browse Gigs
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- APPLICATION MODAL -->
        @if($showApplicationModal && $selectedGig)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
                    
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 rounded-t-2xl flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-white">Apply to Gig</h3>
                        <button wire:click="closeApplicationModal" class="text-white hover:text-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-8">
                        <!-- Gig Info -->
                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                            <h4 class="text-xl font-bold text-gray-900">{{ $selectedGig->title }}</h4>
                            <p class="text-gray-600 mt-2">{{ Str::limit($selectedGig->description, 200) }}</p>
                            <div class="mt-3 flex items-center gap-4 text-sm text-gray-500">
                                <span>Budget: <strong class="text-green-600">{{ $selectedGig->budget_display }}</strong></span>
                                <span>Client: <strong>{{ $selectedGig->client->name }}</strong></span>
                            </div>
                        </div>

                        <!-- Application Form -->
                        <form wire:submit.prevent="submitApplication">
                            <!-- Proposed Price -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Your Proposed Price <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3 text-gray-500 font-bold">$</span>
                                    <input type="number" 
                                           wire:model="proposedPrice"
                                           step="0.01"
                                           min="1"
                                           class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                           placeholder="0.00">
                                </div>
                                @error('proposedPrice')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cover Letter -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Cover Letter <span class="text-red-500">*</span>
                                </label>
                                <textarea wire:model="coverLetter"
                                          rows="6"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                          placeholder="Tell the client why you're the best fit for this project... (minimum 50 characters)"></textarea>
                                <div class="flex justify-between items-center mt-1">
                                    @error('coverLetter')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @else
                                        <p class="text-sm text-gray-500">Minimum 50 characters</p>
                                    @enderror
                                    <p class="text-sm text-gray-400">{{ strlen($coverLetter) }}/1000</p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex gap-4">
                                <button type="submit"
                                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl transition">
                                    Submit Application
                                </button>
                                <button type="button"
                                        wire:click="closeApplicationModal"
                                        class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- APPLICATION DETAIL MODAL -->
        @if($showApplicationDetailModal && $selectedApplication)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full mx-4 max-h-screen overflow-y-auto">
                    
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 rounded-t-2xl flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-white">Application Details</h3>
                        <button wire:click="closeApplicationDetailModal" class="text-white hover:text-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-8">
                        <!-- Gig Info -->
                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                            <h4 class="font-semibold text-gray-700 mb-2">Gig:</h4>
                            <a href="{{ route('gigs.show', $selectedApplication->gig->slug) }}" 
                               target="_blank"
                               class="text-lg font-bold text-green-600 hover:text-green-700">
                                {{ $selectedApplication->gig->title }} ↗
                            </a>
                            <p class="text-sm text-gray-600 mt-2">Client: {{ $selectedApplication->gig->client->name }}</p>
                        </div>

                        <!-- Proposed Price -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-2">Your Proposed Price</h4>
                            <div class="text-3xl font-bold text-green-600">
                                ${{ number_format($selectedApplication->proposed_price, 2) }}
                            </div>
                        </div>

                        <!-- Cover Letter -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-2">Your Cover Letter</h4>
                            <div class="bg-gray-50 rounded-xl p-4 text-gray-700 whitespace-pre-line">
                                {{ $selectedApplication->cover_letter }}
                            </div>
                        </div>

                        <!-- Application Date -->
                        <div class="mb-6 text-sm text-gray-500">
                            Submitted {{ $selectedApplication->created_at->diffForHumans() }}
                            ({{ $selectedApplication->created_at->format('M d, Y \a\t h:i A') }})
                        </div>

                        <!-- Status -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-2">Status</h4>
                            <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold
                                @if($selectedApplication->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($selectedApplication->status === 'accepted') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($selectedApplication->status) }}
                            </span>

                            @if($selectedApplication->status === 'pending')
                                <p class="text-sm text-gray-500 mt-2">Your application is being reviewed by the client.</p>
                            @elseif($selectedApplication->status === 'accepted')
                                <p class="text-sm text-green-600 mt-2">🎉 Congratulations! Your application was accepted.</p>
                            @else
                                <p class="text-sm text-gray-500 mt-2">Unfortunately, your application was not selected.</p>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4">
                            @if($selectedApplication->status === 'pending')
                                <button wire:click="withdrawApplication({{ $selectedApplication->id }})"
                                        wire:confirm="Are you sure you want to withdraw this application?"
                                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl transition">
                                    <i class="fas fa-times mr-2"></i> Withdraw Application
                                </button>
                            @endif
                            
                            <button wire:click="closeApplicationDetailModal"
                                    class="flex-1 border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-3 px-6 rounded-xl transition">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div