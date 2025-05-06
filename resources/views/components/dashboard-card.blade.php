@props(['title', 'value', 'icon', 'color' => 'primary', 'trend' => null, 'trendValue' => null])

@php
    $colorMap = [
        'primary' => [
            'bg' => 'bg-primary-500',
            'text' => 'text-primary-50',
            'gradient' => 'from-primary-600 to-primary-500',
            'border' => 'border-primary-400',
            'shadow' => 'shadow-primary-500/20',
            'hover' => 'hover:shadow-primary-500/30'
        ],
        'secondary' => [
            'bg' => 'bg-secondary-500',
            'text' => 'text-secondary-50',
            'gradient' => 'from-secondary-600 to-secondary-500',
            'border' => 'border-secondary-400',
            'shadow' => 'shadow-secondary-500/20',
            'hover' => 'hover:shadow-secondary-500/30'
        ],
        'success' => [
            'bg' => 'bg-green-500',
            'text' => 'text-green-50',
            'gradient' => 'from-green-600 to-green-500',
            'border' => 'border-green-400',
            'shadow' => 'shadow-green-500/20',
            'hover' => 'hover:shadow-green-500/30'
        ],
        'danger' => [
            'bg' => 'bg-red-500',
            'text' => 'text-red-50',
            'gradient' => 'from-red-600 to-red-500',
            'border' => 'border-red-400',
            'shadow' => 'shadow-red-500/20',
            'hover' => 'hover:shadow-red-500/30'
        ],
        'warning' => [
            'bg' => 'bg-amber-500',
            'text' => 'text-amber-50',
            'gradient' => 'from-amber-600 to-amber-500',
            'border' => 'border-amber-400',
            'shadow' => 'shadow-amber-500/20',
            'hover' => 'hover:shadow-amber-500/30'
        ],
        'info' => [
            'bg' => 'bg-sky-500',
            'text' => 'text-sky-50',
            'gradient' => 'from-sky-600 to-sky-500',
            'border' => 'border-sky-400',
            'shadow' => 'shadow-sky-500/20',
            'hover' => 'hover:shadow-sky-500/30'
        ],
    ];
    
    $colors = $colorMap[$color] ?? $colorMap['primary'];
    
    // Trend icon and color
    $trendIcon = 'fa-arrow-up';
    $trendColor = 'text-green-500';
    
    if ($trend === 'down') {
        $trendIcon = 'fa-arrow-down';
        $trendColor = 'text-red-500';
    } elseif ($trend === 'neutral') {
        $trendIcon = 'fa-minus';
        $trendColor = 'text-amber-500';
    }
@endphp

<div class="card bg-white rounded-xl shadow-sm border border-secondary-100 overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-md {{ $colors['shadow'] }} {{ $colors['hover'] }} group">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-secondary-500 text-sm font-medium">{{ $title }}</h3>
                <div class="mt-2 flex items-baseline">
                    <p class="text-2xl font-semibold text-secondary-900">{{ $value }}</p>
                    
                    @if($trend && $trendValue)
                        <span class="ml-2 text-sm font-medium {{ $trendColor }} flex items-center">
                            <i class="fas {{ $trendIcon }} mr-1 text-xs"></i>
                            {{ $trendValue }}
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="bg-gradient-to-br {{ $colors['gradient'] }} rounded-lg p-3 {{ $colors['text'] }}">
                <i class="{{ $icon }}"></i>
            </div>
        </div>
        
        @if($slot->isNotEmpty())
            <div class="mt-4 pt-3 border-t border-secondary-100">
                <div class="flex justify-between items-center text-sm text-secondary-500">
                    {{ $slot }}
                    <i class="fas fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all"></i>
                </div>
            </div>
        @endif
    </div>
</div>
