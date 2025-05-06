@props([
    'title' => null,
    'icon' => null,
    'iconBg' => 'bg-indigo-100',
    'iconColor' => 'text-indigo-600',
    'hasHeader' => true,
    'headerAction' => null,
    'bordered' => true,
    'animate' => true,
    'class' => ''
])

<div class="bg-white rounded-xl shadow-sm overflow-hidden {{ $bordered ? 'border border-gray-100/50' : '' }} {{ $animate ? 'transform transition hover:shadow-lg hover:-translate-y-0.5 duration-300' : '' }} {{ $class }}">
    @if($hasHeader)
    <div class="card-header flex items-center justify-between p-6 {{ $bordered ? 'border-b border-gray-100' : '' }}">
        <div class="flex items-center">
            @if($icon)
            <div class="p-2 {{ $iconBg }} rounded-lg mr-3 shadow-inner">
                <i class="{{ $icon }} {{ $iconColor }}"></i>
            </div>
            @endif
            <h3 class="text-lg font-semibold text-gray-800">{{ $title }}</h3>
        </div>
        @if($headerAction)
        <div>
            {{ $headerAction }}
        </div>
        @endif
    </div>
    @endif
    
    <div class="p-6">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
    <div class="bg-gray-50 p-4 border-t border-gray-100">
        {{ $footer }}
    </div>
    @endif
</div>