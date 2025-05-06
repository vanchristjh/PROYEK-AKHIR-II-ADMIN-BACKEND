@props(['variant' => 'primary', 'size' => 'md', 'rounded' => 'md', 'icon' => null])

@php
$variants = [
    'primary' => 'bg-primary-100 text-primary-800',
    'secondary' => 'bg-secondary-100 text-secondary-800',
    'success' => 'bg-green-100 text-green-800',
    'danger' => 'bg-red-100 text-red-800',
    'warning' => 'bg-amber-100 text-amber-800',
    'info' => 'bg-sky-100 text-sky-800',
    'light' => 'bg-gray-100 text-gray-800',
    'dark' => 'bg-gray-700 text-gray-100',
];

$sizes = [
    'sm' => 'text-xs px-2 py-0.5',
    'md' => 'text-xs px-2.5 py-1',
    'lg' => 'text-sm px-3 py-1.5'
];

$roundedOptions = [
    'none' => 'rounded-none',
    'sm' => 'rounded-sm',
    'md' => 'rounded-md',
    'lg' => 'rounded-lg',
    'full' => 'rounded-full'
];

$classes = $variants[$variant] . ' ' . $sizes[$size] . ' ' . $roundedOptions[$rounded] . ' inline-flex items-center font-medium';
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <i class="{{ $icon }} {{ $size === 'sm' ? 'mr-1' : 'mr-1.5' }} {{ $size === 'sm' ? 'text-xs' : '' }}"></i>
    @endif
    {{ $slot }}
</span>