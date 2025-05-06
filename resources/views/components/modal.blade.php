@props(['id', 'title' => null, 'maxWidth' => 'md', 'centered' => false])

@php
$maxWidthClass = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
    '5xl' => 'max-w-5xl',
    '6xl' => 'max-w-6xl',
    'full' => 'max-w-full',
][$maxWidth] ?? 'max-w-md';

$alignmentClass = $centered ? 'items-center' : 'items-start mt-16';
@endphp

<div
    x-data="{ show: false }"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    id="{{ $id }}"
    class="fixed inset-0 z-50 overflow-y-auto"
    x-on:open-modal.window="$event.detail == '{{ $id }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $id }}' ? show = false : null"
    x-on:close.stop="show = false"
    x-cloak
    role="dialog"
    aria-labelledby="modal-title-{{ $id }}"
    aria-modal="true"
>
    <div class="flex min-h-screen px-4 text-center sm:block sm:p-0 {{ $alignmentClass }}">
        <!-- Background overlay -->
        <div 
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-secondary-900/50 backdrop-blur-sm transition-opacity"
            x-on:click="show = false"
        ></div>
        
        <!-- Trick the browser into centering the modal contents -->
        <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
        
        <!-- Modal panel -->
        <div 
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:align-middle {{ $maxWidthClass }} w-full"
            x-on:click.stop
        >
            @if ($title)
                <div class="px-6 py-4 border-b border-secondary-100">
                    <h3 class="text-lg font-medium text-secondary-900" id="modal-title-{{ $id }}">{{ $title }}</h3>
                </div>
            @endif
            
            <div class="p-6">
                {{ $slot }}
            </div>
            
            @if (isset($footer))
                <div class="px-6 py-4 bg-secondary-50 border-t border-secondary-100 flex justify-end space-x-3">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    window.openModal = function(modalId) {
        window.dispatchEvent(new CustomEvent('open-modal', { detail: modalId }))
    }
    
    window.closeModal = function(modalId) {
        window.dispatchEvent(new CustomEvent('close-modal', { detail: modalId }))
    }
    
    // For backward compatibility and ease of use, create named functions
    window['open{{ $id }}'] = function() {
        window.openModal('{{ $id }}');
    }
    
    window['close{{ $id }}'] = function() {
        window.closeModal('{{ $id }}');
    }
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>