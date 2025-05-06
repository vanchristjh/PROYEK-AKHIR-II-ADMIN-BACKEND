@props([
    'type' => 'button',
    'color' => 'indigo',
    'size' => 'md',
    'href' => null,
    'icon' => null,
    'iconPosition' => 'left',
    'variant' => 'solid', // Options: solid, outline, link, glass
    'animate' => true,
    'disabled' => false
])

@php
    $colors = [
        'indigo' => [
            'solid' => 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white hover:from-indigo-700 hover:to-indigo-800 shadow-md shadow-indigo-500/20',
            'outline' => 'border border-indigo-600 text-indigo-600 hover:bg-indigo-50',
            'link' => 'text-indigo-600 hover:text-indigo-800 underline-offset-2 hover:underline',
            'glass' => 'bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 border border-white/30'
        ],
        'blue' => [
            'solid' => 'bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 shadow-md shadow-blue-500/20',
            'outline' => 'border border-blue-600 text-blue-600 hover:bg-blue-50',
            'link' => 'text-blue-600 hover:text-blue-800 underline-offset-2 hover:underline',
            'glass' => 'bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 border border-white/30'
        ],
        'red' => [
            'solid' => 'bg-gradient-to-r from-red-600 to-red-700 text-white hover:from-red-700 hover:to-red-800 shadow-md shadow-red-500/20',
            'outline' => 'border border-red-600 text-red-600 hover:bg-red-50',
            'link' => 'text-red-600 hover:text-red-800 underline-offset-2 hover:underline',
            'glass' => 'bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 border border-white/30'
        ],
        'green' => [
            'solid' => 'bg-gradient-to-r from-green-600 to-green-700 text-white hover:from-green-700 hover:to-green-800 shadow-md shadow-green-500/20',
            'outline' => 'border border-green-600 text-green-600 hover:bg-green-50',
            'link' => 'text-green-600 hover:text-green-800 underline-offset-2 hover:underline',
            'glass' => 'bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 border border-white/30'
        ],
        'amber' => [
            'solid' => 'bg-gradient-to-r from-amber-600 to-amber-700 text-white hover:from-amber-700 hover:to-amber-800 shadow-md shadow-amber-500/20',
            'outline' => 'border border-amber-600 text-amber-600 hover:bg-amber-50',
            'link' => 'text-amber-600 hover:text-amber-800 underline-offset-2 hover:underline',
            'glass' => 'bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 border border-white/30'
        ],
        'gray' => [
            'solid' => 'bg-gradient-to-r from-gray-600 to-gray-700 text-white hover:from-gray-700 hover:to-gray-800 shadow-md shadow-gray-500/20',
            'outline' => 'border border-gray-300 text-gray-700 hover:bg-gray-50',
            'link' => 'text-gray-600 hover:text-gray-800 underline-offset-2 hover:underline',
            'glass' => 'bg-black/10 backdrop-blur-sm text-white hover:bg-black/20 border border-white/20'
        ]
    ];
    
    $sizes = [
        'xs' => 'px-2 py-1 text-xs',
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
        'xl' => 'px-6 py-3 text-lg'
    ];
    
    $btnClass = 'inline-flex items-center justify-center font-medium rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-' . $color . '-500 ' . $sizes[$size] . ' ' . $colors[$color][$variant];
    
    if($animate) {
        $btnClass .= ' transform hover:-translate-y-0.5 active:translate-y-0 duration-200';
    }
    
    if($disabled) {
        $btnClass .= ' opacity-50 cursor-not-allowed';
    }
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $btnClass]) }}>
        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }} mr-2"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} ml-2"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $btnClass]) }} {{ $disabled ? 'disabled' : '' }}>
        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }} mr-2"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} ml-2"></i>
        @endif
    </button>
@endif