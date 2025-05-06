@props([
    'title' => '',
    'subtitle' => '',
    'icon' => 'fas fa-bolt',
    'color' => 'blue',
    'link' => '#',
    'animate' => true
])

@php
    $colors = [
        'indigo' => [
            'bg' => 'hover:from-indigo-50 hover:to-indigo-50',
            'icon-bg' => 'from-indigo-400 to-indigo-600',
            'shadow' => 'shadow-indigo-200/50',
            'hover' => 'hover:text-indigo-600'
        ],
        'blue' => [
            'bg' => 'hover:from-blue-50 hover:to-blue-50',
            'icon-bg' => 'from-blue-400 to-blue-600',
            'shadow' => 'shadow-blue-200/50',
            'hover' => 'hover:text-blue-600'
        ],
        'green' => [
            'bg' => 'hover:from-green-50 hover:to-green-50',
            'icon-bg' => 'from-green-400 to-green-600',
            'shadow' => 'shadow-green-200/50',
            'hover' => 'hover:text-green-600'
        ],
        'red' => [
            'bg' => 'hover:from-red-50 hover:to-red-50',
            'icon-bg' => 'from-red-400 to-red-600',
            'shadow' => 'shadow-red-200/50',
            'hover' => 'hover:text-red-600'
        ],
        'amber' => [
            'bg' => 'hover:from-amber-50 hover:to-amber-50',
            'icon-bg' => 'from-amber-400 to-amber-600',
            'shadow' => 'shadow-amber-200/50',
            'hover' => 'hover:text-amber-600'
        ],
        'purple' => [
            'bg' => 'hover:from-purple-50 hover:to-purple-50',
            'icon-bg' => 'from-purple-400 to-purple-600',
            'shadow' => 'shadow-purple-200/50',
            'hover' => 'hover:text-purple-600'
        ],
        'pink' => [
            'bg' => 'hover:from-pink-50 hover:to-pink-50',
            'icon-bg' => 'from-pink-400 to-pink-600',
            'shadow' => 'shadow-pink-200/50',
            'hover' => 'hover:text-pink-600'
        ]
    ];
@endphp

<a href="{{ $link }}" class="quick-action block bg-gradient-to-r from-gray-50 to-gray-100 {{ $colors[$color]['bg'] }} border border-gray-200 p-4 rounded-xl transition-all duration-300 {{ $animate ? 'hover:-translate-y-2 hover:shadow-md' : '' }} group">
    <div class="flex items-center">
        <div class="flex-shrink-0 h-14 w-14 rounded-xl bg-gradient-to-br {{ $colors[$color]['icon-bg'] }} flex items-center justify-center text-white shadow-md group-hover:{{ $colors[$color]['shadow'] }} transition-all duration-300 group-hover:scale-110">
            <i class="{{ $icon }} text-lg"></i>
        </div>
        <div class="ml-4">
            <h4 class="text-sm font-semibold text-gray-800 group-{{ $colors[$color]['hover'] }} transition-colors">{{ $title }}</h4>
            <p class="text-xs text-gray-500 mt-1">{{ $subtitle }}</p>
        </div>
    </div>
</a>
