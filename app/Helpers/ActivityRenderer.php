<?php

namespace App\Helpers;

class ActivityRenderer
{
    /**
     * Render HTML for an activity item
     *
     * @param mixed $activity Activity object with type, description, created_at and user properties
     * @param int $index Index for animation delay
     * @return string The HTML for the activity item
     */
    public static function render($activity, $index = 0)
    {
        $activityColor = ActivityIconHelper::getColor($activity->type ?? 'system');
        $activityIcon = ActivityIconHelper::getIcon($activity->type ?? 'system');
        
        $createdAt = $activity->created_at;
        if (!($createdAt instanceof \Carbon\Carbon)) {
            $createdAt = \Carbon\Carbon::parse($createdAt);
        }
        $formattedTime = $createdAt->format('d M Y, H:i');
        
        $html = <<<HTML
            <div class="activity-item flex items-center py-3 px-6 hover:bg-blue-50/20 transition-all duration-150 hover:-translate-y-0.5 transform animate-item" 
                 style="animation-delay: {$index}00ms" 
                 data-activity-id="{$activity->id}">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-{$activityColor}-400 to-{$activityColor}-600 flex items-center justify-center text-white shadow-sm">
                        <i class="fas fa-{$activityIcon} text-sm"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-800">{$activity->description}</p>
                    <p class="text-xs text-gray-500 flex items-center">
                        <i class="fas fa-clock mr-1 opacity-70"></i>
                        {$formattedTime}
        HTML;
        
        if (isset($activity->user)) {
            $html .= <<<HTML
                        <span class="ml-2 px-2 py-0.5 bg-blue-50 text-blue-600 text-xs rounded-full">{$activity->user->name}</span>
            HTML;
        }
        
        $html .= <<<HTML
                    </p>
                </div>
            </div>
        HTML;
        
        return $html;
    }
}
