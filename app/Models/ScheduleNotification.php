<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_schedule_id',
        'user_id',
        'title',
        'message',
        'notification_time',
        'sent_at',
        'is_read',
        'type',
    ];

    protected $casts = [
        'notification_time' => 'datetime',
        'sent_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    public function schedule()
    {
        return $this->belongsTo(ClassSchedule::class, 'class_schedule_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
    
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    public function scopeRecent($query)
    {
        return $query->orderBy('notification_time', 'desc');
    }
    
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
}
