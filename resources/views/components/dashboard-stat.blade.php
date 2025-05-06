@props([
    'title' => '',
    'value' => '0',
    'icon' => 'fas fa-chart-bar',
    'color' => 'indigo', 
    'link' => null,
    'linkText' => 'View details',
    'animate' => true
])

@php
    $colors = [
        'indigo' => [
            'bg' => 'from-white to-indigo-50',
            'icon-bg' => 'bg-indigo-100',
            'icon-text' => 'text-indigo-600',
            'link' => 'text-indigo-600 hover:text-indigo-800'
        ],
        'blue' => [
            'bg' => 'from-white to-blue-50',
            'icon-bg' => 'bg-blue-100',
            'icon-text' => 'text-blue-600',
            'link' => 'text-blue-600 hover:text-blue-800'
        ],
        'green' => [
            'bg' => 'from-white to-green-50',
            'icon-bg' => 'bg-green-100',
            'icon-text' => 'text-green-600',
            'link' => 'text-green-600 hover:text-green-800'
        ],
        'red' => [
            'bg' => 'from-white to-red-50',
            'icon-bg' => 'bg-red-100',
            'icon-text' => 'text-red-600',
            'link' => 'text-red-600 hover:text-red-800'
        ],
        'amber' => [
            'bg' => 'from-white to-amber-50',
            'icon-bg' => 'bg-amber-100',
            'icon-text' => 'text-amber-600',
            'link' => 'text-amber-600 hover:text-amber-800'
        ],
        'purple' => [
            'bg' => 'from-white to-purple-50',
            'icon-bg' => 'bg-purple-100',
            'icon-text' => 'text-purple-600',
            'link' => 'text-purple-600 hover:text-purple-800'
        ],
        'pink' => [
            'bg' => 'from-white to-pink-50',
            'icon-bg' => 'bg-pink-100',
            'icon-text' => 'text-pink-600',
            'link' => 'text-pink-600 hover:text-pink-800'
        ],
        'gray' => [
            'bg' => 'from-white to-gray-50',
            'icon-bg' => 'bg-gray-100',
            'icon-text' => 'text-gray-600',
            'link' => 'text-gray-600 hover:text-gray-800'
        ]
    ];
@endphp

<div class="dashboard-card bg-gradient-to-br {{ $colors[$color]['bg'] }} rounded-xl shadow-md p-6 transition-all hover:shadow-lg border border-gray-100/50 overflow-hidden relative transform {{ $animate ? 'hover:-translate-y-1' : '' }} duration-300">
    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-{{ $color }}-50 rounded-full"></div>
    <div class="flex items-start relative z-10">
        <div class="p-3 rounded-xl {{ $colors[$color]['icon-bg'] }} {{ $colors[$color]['icon-text'] }} shadow-inner ring-4 ring-white">
            <i class="{{ $icon }} text-xl"></i>
        </div>
        <div class="ml-4 flex-1">
            <h3 class="text-sm font-medium text-gray-500">{{ $title }}</h3>
            <p class="card-number text-2xl font-bold text-gray-800 my-1 counter-value {{ $animate ? 'floating-element' : '' }}">{{ $value }}</p>
            @if($link)
            <div class="mt-2">
                <a href="{{ $link }}" class="text-sm {{ $colors[$color]['link'] }} inline-flex items-center group">
                    <span>{{ $linkText }}</span>
                    <i class="fas fa-arrow-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
