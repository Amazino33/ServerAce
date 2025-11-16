@props(['title', 'closeFunction'])

<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full mx-4 max-h-screen overflow-y-auto">

        {{-- Modal Header --}}
        <div
            class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 rounded-t-2xl flex justify-between items-center">
            <h3 class="text-2xl font-bold text-white">{{ $title }}</h3>
            <button wire:click="{{ $closeFunction }}" class="text-white hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        {{-- Modal Body --}}
        {{ $slug }}
    </div>
</div>