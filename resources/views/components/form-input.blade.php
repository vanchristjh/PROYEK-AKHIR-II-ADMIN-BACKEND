@props(['label', 'name', 'type' => 'text', 'placeholder' => '', 'value' => '', 'required' => false, 'icon' => null])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-secondary-700 mb-1.5">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    <div class="relative rounded-md shadow-sm">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="{{ $icon }} text-secondary-400"></i>
            </div>
        @endif
        
        <input 
            type="{{ $type }}" 
            id="{{ $name }}" 
            name="{{ $name }}" 
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}" 
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge([
                'class' => 'block w-full rounded-md border-secondary-300 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 shadow-sm transition-colors text-secondary-800 placeholder-secondary-400 ' . 
                ($icon ? 'pl-10' : '')
            ]) }}
        >
        
        @error($name)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
        @enderror
    </div>
    
    @error($name)
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
    @enderror
    
    @if(isset($hint))
        <p class="mt-1.5 text-xs text-secondary-500">{{ $hint }}</p>
    @endif
</div>
