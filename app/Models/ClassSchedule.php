<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'teacher_id',
        'subject',
        'day_of_week',
        'start_time',
        'end_time',
        'room',
        'academic_year',
        'semester',
        'description',
        'created_by',
        'is_active',
        'notification_enabled',
        'notify_minutes_before',
        'notify_by_email',
        'notify_by_push',
        'last_notification_sent',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
        'notification_enabled' => 'boolean',
        'notify_by_email' => 'boolean',
        'notify_by_push' => 'boolean',
        'last_notification_sent' => 'datetime',
    ];

    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function notifications()
    {
        return $this->hasMany(ScheduleNotification::class);
    }

    public function students()
    {
        return $this->class ? $this->class->students : collect();
    }

    public function getDurationAttribute()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }

    public function getFormattedDayAttribute()
    {
        $days = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu',
        ];

        return $days[strtolower($this->day_of_week)] ?? $this->day_of_week;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeForNotification($query)
    {
        return $query->where('notification_enabled', true)
                    ->where('is_active', true);
    }
    
    public function shouldSendNotification()
    {
        if (!$this->notification_enabled || !$this->is_active) {
            return false;
        }
        
        // Get the current day of week
        $currentDayOfWeek = strtolower(now()->format('l'));
        
        // Check if it's the right day for the schedule
        if ($this->day_of_week !== $currentDayOfWeek) {
            return false;
        }
        
        // Calculate the notification time
        $notificationTime = clone $this->start_time;
        $notificationTime->subMinutes($this->notify_minutes_before ?? 15);
        
        // Check if it's time to send the notification
        $now = now();
        $timeDifference = $now->diffInMinutes($notificationTime, false);
        
        // Return true if we're within 1 minute of the notification time and haven't sent a notification yet today
        return $timeDifference >= 0 && $timeDifference <= 1 && 
               (!$this->last_notification_sent || $this->last_notification_sent->format('Y-m-d') !== $now->format('Y-m-d'));
    }
}
