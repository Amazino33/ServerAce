@props(['tabName', 'displayedName', 'count' => '', 'faIcon'])

<button wire:click="setActiveTab('{{ $tabName }}')"
        class="px-6 py-4 text-sm font-medium border-b-2 transition {{ $activeTab === '{{ $tabName }}' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
    <i class="fas fa-{{ $faIcon }} mr-2"></i>{{ $displayedName }} ({{ $count }})
</button>