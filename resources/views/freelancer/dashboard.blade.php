<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">Find Your Next Gig</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow-lg">
                    <p class="text-sm opacity-90">Available Balance</p>
                    <p class="text-4xl font-bold">$2,450</p>
                    <a href="#" class="text-sm underline mt-2 inline-block">Withdraw →</a>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border">
                    <p class="text-sm text-gray-600">Active Bids</p>
                    <p class="text-4xl font-bold text-blue-600">12</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border">
                    <p class="text-sm text-gray-600">Success Rate</p>
                    <p class="text-4xl font-bold text-green-600">94%</p>
                </div>
            </div>

            <!-- Latest Gigs -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Latest Gigs</h3>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        View All Gigs
                    </button>
                </div>

                <div class="space-y-6">
                    @for($i = 0; $i < 4; $i++)
                    <div class="border rounded-lg p-6 hover:shadow-xl transition-shadow cursor-pointer">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-lg font-bold text-blue-600">Urgent: Docker Swarm Setup</h4>
                                <p class="text-gray-600 mt-1">Need expert to migrate 12 nodes to Docker Swarm with Traefik</p>
                                <div class="flex items-center space-x-4 mt-3">
                                    <span class="text-sm bg-gray-100 px-3 py-1 rounded-full">Docker</span>
                                    <span class="text-sm bg-gray-100 px-3 py-1 rounded-full">Kubernetes</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-green-600">$850</p>
                                <p class="text-sm text-gray-500">3 bids</p>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-sm text-gray-500">Posted 10 min ago</span>
                            <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Place Bid
                            </button>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

        </div>
    </div>
</x-app-layout>