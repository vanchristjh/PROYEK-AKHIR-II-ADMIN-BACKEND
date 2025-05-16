<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'subject_id',
        'teacher_id',
        'day',
        'start_time',
        'end_time',
        'room',
        'created_by',
        'school_year',
        'notes',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        // First try the standard Teacher model
        $relation = $this->belongsTo(Teacher::class);
        
        // If no teacher found but we have a teacher_id, try looking in User model
        if (!$relation->getResults() && $this->teacher_id) {
            // Look for a user with this ID
            $user = User::find($this->teacher_id);
            
            if ($user) {
                return $this->belongsTo(User::class, 'teacher_id');
            }
        }
        
        return $relation;
    }

    // Get teacher name regardless of where it's stored
    public function getTeacherNameAttribute()
    {
        // First try to get from regular teacher relation
        if ($this->teacher && isset($this->teacher->name)) {
            return $this->teacher->name;
        }
        
        // Try from User model
        if ($this->teacher_id) {
            $user = User::find($this->teacher_id);
            if ($user) {
                return $user->name . ' (User)';
            }
            return 'ID: ' . $this->teacher_id;
        }
        
        return 'Tidak Ada';
    }

    // Handle day as both string and number
    public function getDayNameAttribute()
    {
        if (is_numeric($this->day)) {
            $days = [
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
                7 => 'Minggu'
            ];
            
            return $days[$this->day] ?? $this->day;
        }
        
        return $this->day;
    }
}
