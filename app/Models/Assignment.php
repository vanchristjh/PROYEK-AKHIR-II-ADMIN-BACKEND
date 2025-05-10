<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Assignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'subject_id',
        'classroom_id',
        'teacher_id',
        'file',
        'deadline',
        'max_score',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'deadline' => 'datetime',
    ];

    /**
     * Get the submissions for the assignment.
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Get the user who created this assignment.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the classroom associated with this assignment.
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Get the subject associated with this assignment.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher associated with this assignment.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // Check if current user has submitted this assignment
    public function isSubmitted()
    {
        return $this->submissions()->where('student_id', Auth::id())->exists();
    }

    // Get submission by current user
    public function getSubmissionByCurrentUser()
    {
        return $this->submissions()->where('student_id', Auth::id())->first();
    }

    // Check if assignment deadline has passed
    public function isExpired()
    {
        return $this->deadline < now();
    }

    // Get remaining time until deadline in human readable format
    public function getRemainingTimeAttribute()
    {
        if ($this->isExpired()) {
            return 'Deadline terlewat';
        }
        
        $now = now();
        $diffDays = $this->deadline->diffInDays($now);
        $diffHours = $this->deadline->diffInHours($now) % 24;
        $diffMinutes = $this->deadline->diffInMinutes($now) % 60;
        
        $result = '';
        if ($diffDays > 0) {
            $result .= $diffDays . ' hari ';
        }
        if ($diffHours > 0 || $diffDays > 0) {
            $result .= $diffHours . ' jam ';
        }
        $result .= $diffMinutes . ' menit';
        
        return $result;
    }
}
