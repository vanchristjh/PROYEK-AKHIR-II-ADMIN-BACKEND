@props(['type' => 'info', 'title' => null, 'dismissable' => false])

@php
$types = [
    'info' => [
        'bg' => 'bg-sky-50',
        'text' => 'text-sky-800',
        'border' => 'border-sky-200',
        'icon' => 'fas fa-info-circle text-sky-500'
    ],
    'success' => [
        'bg' => 'bg-green-50',
        'text' => 'text-green-800',
        'border' => 'border-green-200',
        'icon' => 'fas fa-check-circle text-green-500'
    ],
    'warning' => [
        'bg' => 'bg-amber-50',
        'text' => 'text-amber-800',
        'border' => 'border-amber-200',
        'icon' => 'fas fa-exclamation-triangle text-amber-500'
    ],
    'error' => [
        'bg' => 'bg-red-50',
        'text' => 'text-red-800',
        'border' => 'border-red-200',
        'icon' => 'fas fa-exclamation-circle text-red-500'
    ],
];

$style = $types[$type];
@endphp

<div {{ $attributes->merge(['class' => "{$style['bg']} border-l-4 {$style['border']} p-4 rounded-md"]) }}>
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="{{ $style['icon'] }}"></i>
        </div>
        <div class="ml-3 flex-1">
            @if($title)
                <h3 class="text-sm font-medium {{ $style['text'] }}">{{ $title }}</h3>
            @endif
            
            <div class="{{ $title ? 'mt-2' : '' }} text-sm {{ $style['text'] }}">
                {{ $slot }}
            </div>
        </div>
        
        @if($dismissable)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" class="inline-flex {{ $style['bg'] }} rounded-md p-1.5 {{ $style['text'] }} hover:bg-opacity-80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-{{ substr($type, 0, strpos($type, '-') ?: strlen($type)) }}-50 focus:ring-{{ substr($type, 0, strpos($type, '-') ?: strlen($type)) }}-500 dismiss-alert">
                        <span class="sr-only">Dismiss</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

@if($dismissable)
<script>
    document.querySelectorAll('.dismiss-alert').forEach(button => {
        button.addEventListener('click', () => {
            const alert = button.closest('[role="alert"]');
            alert.classList.add('opacity-0');
            setTimeout(() => {
                alert.remove();
            }, 300);
        });
    });
</script>
@endif