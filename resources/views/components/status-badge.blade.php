@props(['gig'])

@php
    $status = is_object($gig->status) ? $gig->status->value : $gig->status;
    
    $statusConfig = [
        'open' => [
            'bg' => 'bg-green-100',
            'text' => 'text-green-800',
            'border' => 'border-green-500',
            'icon' => 'fa-check-circle',
            'label' => 'Open for Applications',
            'dot' => 'bg-green-500'
        ],
        'closed' => [
            'bg' => 'bg-gray-100',
            'text' => 'text-gray-800',
            'border' => 'border-gray-500',
            'icon' => 'fa-lock',
            'label' => 'Closed',
            'dot' => 'bg-gray-500'
        ],
        'in_progress' => [
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-800',
            'border' => 'border-blue-500',
            'icon' => 'fa-spinner',
            'label' => 'In Progress',
            'dot' => 'bg-blue-500'
        ],
        'completed' => [
            'bg' => 'bg-purple-100',
            'text' => 'text-purple-800',
            'border' => 'border-purple-500',
            'icon' => 'fa-check-double',
            'label' => 'Completed',
            'dot' => 'bg-purple-500'
        ]
    ];
    
    $config = $statusConfig[$status] ?? $statusConfig['open'];
@endphp

<div class="mt-6">
    <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold border-2 {{ $config['bg'] }} {{ $config['text'] }} {{ $config['border'] }}">
        <span class="w-2 h-2 rounded-full {{ $config['dot'] }} mr-2 animate-pulse"></span>
        <i class="fas {{ $config['icon'] }} mr-2"></i>
        {{ $config['label'] }}
    </div>
</div>