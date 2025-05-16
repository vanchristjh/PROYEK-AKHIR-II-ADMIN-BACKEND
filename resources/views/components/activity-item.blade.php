@php
    $activityColor = App\Helpers\ActivityIconHelper::getColor($activity->type ?? 'system');
    $activityIcon = App\Helpers\ActivityIconHelper::getIcon($activity->type ?? 'system');
@endphp

<div class="activity-item flex items-center py-3 px-6 hover:bg-blue-50/20 transition-all duration-150 hover:-translate-y-0.5 transform animate-item" 
     style="animation-delay: {{ $index * 100 }}ms" 
     data-activity-id="{{ $activity->id ?? $index }}">
    <div class="flex-shrink-0">
        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-{{ $activityColor }}-400 to-{{ $activityColor }}-600 flex items-center justify-center text-white shadow-sm">
            <i class="fas fa-{{ $activityIcon }} text-sm"></i>
        </div>
    </div>
    <div class="ml-4">
        <p class="text-sm font-medium text-gray-800">{{ $activity->description }}</p>
        <p class="text-xs text-gray-500 flex items-center">
            <i class="fas fa-clock mr-1 opacity-70"></i>
            {{ $activity->created_at->format('d M Y, H:i') }}
            @if($activity->user)
            <span class="ml-2 px-2 py-0.5 bg-blue-50 text-blue-600 text-xs rounded-full">{{ $activity->user->name }}</span>
            @endif
        </p>
    </div>
</div>
