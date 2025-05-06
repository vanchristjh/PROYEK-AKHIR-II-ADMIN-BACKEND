@props([
    'title' => '', 
    'subtitle' => '', 
    'color' => 'indigo',
    'icon' => null,
    'hasParticles' => true
])

@php
    $colors = [
        'indigo' => 'from-indigo-500 via-indigo-600 to-purple-600',
        'blue' => 'from-blue-500 via-blue-600 to-indigo-600',
        'green' => 'from-green-500 via-teal-500 to-emerald-500',
        'red' => 'from-red-500 via-rose-500 to-pink-600',
        'amber' => 'from-amber-400 via-orange-500 to-amber-600',
        'purple' => 'from-purple-500 via-violet-500 to-purple-600',
    ];
    
    $gradient = $colors[$color] ?? $colors['indigo'];
@endphp

<div class="bg-gradient-to-r {{ $gradient }} animate-gradient-x rounded-xl shadow-xl p-6 mb-8 text-white relative overflow-hidden">
    @if($hasParticles)
    <div class="particles-container absolute inset-0 pointer-events-none"></div>
    @endif
    
    <div class="absolute right-0 top-0 opacity-10 transform hover:scale-110 transition-transform duration-700">
        @if($icon)
            <i class="{{ $icon }} text-9xl -mt-4 -mr-4"></i>
        @endif
    </div>
    
    <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
    <div class="absolute right-1/3 -top-12 w-36 h-36 bg-white/10 rounded-full blur-3xl"></div>
    
    <div class="relative animate-fade-in z-10">
        <h2 class="text-2xl font-bold mb-2">{{ $title }}</h2>
        <p class="text-{{ $color }}-100">{{ $subtitle }}</p>
        
        <div class="mt-4 flex flex-wrap gap-3">
            {{ $slot }}
        </div>
    </div>
</div>
