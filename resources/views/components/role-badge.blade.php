<span class="flex justify-center items-center w-auto max-h-10 px-3 py-1 rounded-full text-white text-sm font-semibold
    @if (auth()->user()->isAdmin()) bg-red-600
    @elseif (auth()->user()->isClient()) bg-blue-600
    @elseif (auth()->user()->isFreelancer()) bg-green-600
    @else bg-gray-600 @endif
">
    {{ auth()->user()->role->label() }}
</span>