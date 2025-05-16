<?php

namespace App\Traits;

use App\Models\Activity;

trait LogsActivity
{
    /**
     * Log system activity
     * 
     * @param string $action The action performed
     * @param string $description Additional description
     * @param string $type Activity type for icon selection
     * @param array $metadata Optional additional metadata
     * @return \App\Models\Activity|null
     */
    public function logActivity($action, $description, $type = 'system', $metadata = [])
    {
        try {
            // Check if we have an Activity model and class exists
            if (class_exists('\App\Models\Activity')) {
                $activity = Activity::create([
                    'user_id' => auth()->id(),
                    'action' => $action,
                    'description' => $description,
                    'type' => $type,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'metadata' => $metadata
                ]);
                
                return $activity;
            } else {
                // Log to file if no model exists
                \Log::info('System Activity: ' . $action . ' - ' . $description . ' (Type: ' . $type . ')');
            }
        } catch (\Exception $e) {
            \Log::error('Failed to log activity: ' . $e->getMessage());
        }
        
        return null;
    }

    /**
     * Get recent system activities
     * 
     * @param int $limit Number of activities to return
     * @param string|null $type Filter by activity type
     * @param int|null $userId Filter by user ID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentActivities($limit = 10, $type = null, $userId = null)
    {
        if (!class_exists('\App\Models\Activity')) {
            return collect([]);
        }
        
        $query = Activity::with('user')->latest();
        
        // Apply type filter if provided
        if ($type) {
            $query->where('type', $type);
        }
        
        // Apply user filter if provided
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->take($limit)->get();
    }
}
