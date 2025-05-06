@props(['title' => '', 'icon' => null, 'color' => 'indigo', 'actions' => null])

@php
    $colors = [
        'indigo' => 'bg-indigo-100 text-indigo-600',
        'blue' => 'bg-blue-100 text-blue-600',
        'green' => 'bg-green-100 text-green-600',
        'red' => 'bg-red-100 text-red-600',
        'amber' => 'bg-amber-100 text-amber-600',
        'purple' => 'bg-purple-100 text-purple-600'
    ];
    
    $bgColor = $colors[$color] ?? $colors['indigo'];
@endphp

<div class="flex items-center justify-between mb-6">
    <h3 class="text-lg font-medium text-gray-800 flex items-center">
        @if($icon)
        <div class="p-2 {{ $bgColor }} rounded-lg mr-3 shadow-inner">
            <i class="{{ $icon }}"></i>
        </div>
        @endif
        <span class="text-shadow-sm">{{ $title }}</span>
    </h3>
    
    @if($actions)
    <div class="flex space-x-3">
        {{ $actions }}
    </div>
    @endif
</div>
