@props(['color', 'attribute', 'value', 'svg_d'])

<div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-{{ $color }}-500">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-600 font-semibold">{{ $attribute }}</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $value }}</p>
        </div>
        <div class="bg-{{ $color }}-100 rounded-full p-3">
            <svg class="w-8 h-8 text-{{ $color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="{{ $svg_d }}">
                </path>
            </svg>
        </div>
    </div>
</div>