<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'classroom_id',
        'subject_id',
        'teacher_id',
        'attachment_path',
        'deadline',
        'max_score',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    // Teacher who created the assignment
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Classroom this assignment is for
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    // Subject this assignment belongs to
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Student submissions for this assignment
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    // Check if the assignment is past its deadline
    public function isExpired()
    {
        return $this->deadline && Carbon::now()->greaterThan($this->deadline);
    }

    // Get remaining time as a human-readable string
    public function getRemainingTimeAttribute()
    {
        if (!$this->deadline) {
            return 'No deadline';
        }

        $now = Carbon::now();
        
        if ($now->greaterThan($this->deadline)) {
            return 'Expired';
        }

        return $now->diffForHumans($this->deadline, ['parts' => 2]);
    }
}
