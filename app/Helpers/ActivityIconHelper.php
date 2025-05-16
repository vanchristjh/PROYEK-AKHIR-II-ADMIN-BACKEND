<?php

namespace App\Helpers;

class ActivityIconHelper
{
    /**
     * Get icon name for activity type
     * 
     * @param string $type Activity type
     * @return string Icon name for Font Awesome
     */
    public static function getIcon($type)
    {
        $icons = self::getIconMap();
        return $icons[$type] ?? 'bell';
    }
    
    /**
     * Get color name for activity type
     * 
     * @param string $type Activity type
     * @return string Tailwind color name
     */
    public static function getColor($type)
    {
        $colors = self::getColorMap();
        return $colors[$type] ?? 'blue';
    }
    
    /**
     * Get mapping of activity types to Font Awesome icons
     * 
     * @return array
     */
    public static function getIconMap()
    {
        return [
            'login' => 'sign-in-alt',
            'logout' => 'sign-out-alt',
            'user_created' => 'user-plus',
            'user_updated' => 'user-edit',
            'user_deleted' => 'user-minus',
            'profile_updated' => 'id-card',
            'password_changed' => 'key',
            'refresh' => 'sync',
            'system' => 'cog',
            'announcement' => 'bullhorn',
            'schedule_update' => 'calendar-alt',
            'subject' => 'book',
            'classroom' => 'chalkboard',
            'assignment' => 'tasks',
            'submission' => 'file-upload',
            'grade' => 'star',
            'error' => 'exclamation-triangle'
        ];
    }
    
    /**
     * Get mapping of activity types to Tailwind colors
     * 
     * @return array
     */
    public static function getColorMap()
    {
        return [
            'login' => 'blue',
            'logout' => 'gray',
            'user_created' => 'green',
            'user_updated' => 'indigo',
            'user_deleted' => 'red',
            'profile_updated' => 'purple',
            'password_changed' => 'yellow',
            'refresh' => 'cyan',
            'system' => 'gray',
            'announcement' => 'amber',
            'schedule_update' => 'indigo',
            'subject' => 'emerald',
            'classroom' => 'violet',
            'assignment' => 'lime',
            'submission' => 'sky',
            'grade' => 'amber',
            'error' => 'red'
        ];
    }
}
