<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">My Projects</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @livewire('client.create-gig')

            <!-- Post New Gig Button -->
            <div class="mb-8 text-center">
                <a href="#" class="inline-flex items-center px-8 py-4 bg-green-600 text-white text-lg font-bold rounded-xl hover:bg-green-700 shadow-lg">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Post a New Gig
                </a>
            </div>

            <!-- Active Projects -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @for($i = 0; $i < 6; $i++)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-32 flex items-center justify-center">
                        <span class="text-white text-4xl font-bold">SA</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold">Fix My SSL Certificate</h3>
                        <p class="text-gray-600 text-sm mt-2">Let's Encrypt expired, need auto-renew setup</p>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-2xl font-bold text-green-600">$240</span>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold">IN PROGRESS</span>
                        </div>
                        <div class="mt-4 flex items-center space-x-2">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" class="w-10 h-10 rounded-full" alt="Freelancer">
                            <div>
                                <p class="text-sm font-medium">Alex Chen</p>
                                <p class="text-xs text-gray-500">3 hours ago</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>

        </div>
    </div>
</x-app-layout>