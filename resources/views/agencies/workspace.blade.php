<x-app-layout>
    <x-slot name="title">
        {{ $agency->name }} Workspace
    </x-slot>

    <div x-data="{ inviteModalOpen: {{ $errors->has('email') ? 'true' : 'false' }} }" class="min-h-screen bg-gray-50 py-8 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8 flex justify-between items-start">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $agency->name }}</h1>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full uppercase">
                            Workspace
                        </span>
                    </div>
                    <p class="text-gray-600">{{ $agency->description ?? 'No description provided.' }}</p>
                </div>

                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 font-medium flex items-center gap-2 transition">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                        <h3 class="font-bold text-gray-900 mb-4">Agency Overview</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                                <span class="text-gray-500">Active Bids</span>
                                <span class="font-bold text-gray-900">0</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                                <span class="text-gray-500">Won Projects</span>
                                <span class="font-bold text-gray-900">0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Total Earnings</span>
                                <span class="font-bold text-green-600">$0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                            <h3 class="font-bold text-gray-900">Team Members ({{ $teamMembers->count() }})</h3>

                            <button @click="inviteModalOpen = true" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition flex items-center gap-2">
                                <i class="fas fa-user-plus"></i> Invite Member
                            </button>
                        </div>

                        <ul class="divide-y divide-gray-200">
                            @foreach($teamMembers as $member)
                                <li class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold text-lg">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900">
                                                {{ $member->name }}
                                                @if($member->id === auth()->id())
                                                    <span class="text-gray-400 text-sm font-normal">(You)</span>
                                                @endif
                                            </p>
                                            <p class="text-sm text-gray-500">{{ $member->email }}</p>
                                        </div>
                                    </div>

                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                        {{ $member->pivot->role === 'owner' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $member->pivot->role }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @if(isset($pendingInvitations) && $pendingInvitations->count() > 0)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="font-bold text-gray-900">Pending Invitations ({{ $pendingInvitations->count() }})</h3>
                            </div>
                            <ul class="divide-y divide-gray-200">
                                @foreach($pendingInvitations as $invite)
                                    <li class="px-6 py-4 flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $invite->email }}</p>
                                                <p class="text-xs text-gray-500">Sent {{ $invite->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full uppercase">
                                            Pending
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <div x-show="inviteModalOpen" 
             style="display: none;" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="inviteModalOpen" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100" 
                     x-transition:leave-end="opacity-0" 
                     class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" 
                     @click="inviteModalOpen = false" 
                     aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="inviteModalOpen" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    <form action="{{ route('agencies.invitations.store', $agency->slug) }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-paper-plane text-green-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                        Invite Team Member
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-4">
                                            Send an invitation to a freelancer to join <strong>{{ $agency->name }}</strong>.
                                        </p>
                                        
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" 
                                            placeholder="freelancer@example.com">
                                        
                                        @error('email')
                                            <p class="mt-2 text-sm text-red-600 font-bold">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition">
                                Send Invitation
                            </button>
                            <button type="button" @click="inviteModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>