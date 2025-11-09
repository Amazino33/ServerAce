<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-6">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h1 class="text-4xl font-bold text-green-600 mb-4">{{ $gig->title }}</h1>
            <p class="text-gray-600 mb-6">Posted by: {{ $gig->client->name }}</p>
            <div class="bg-gray-100 p-6 rounded-xl mb-6">
                <p class="text-lg whitespace-pre-wrap">{{ $gig->description }}</p>
            </div>
            <div class="text-2xl font-bold text-green-700">
                Budget: {{ $gig->getBudgetDisplayAttribute() }}
            </div>
            <div class="mt-4">
                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full font-semibold">
                    {{ $gig->status->label() }}
                </span>
            </div>
            <div class="mt-8 text-sm text-gray-500">
                Gig ID: {{ $gig->id }} | Created: {{ $gig->created_at->diffForHumans() }}
            </div>
        </div>
    </div>
</x-app-layout>