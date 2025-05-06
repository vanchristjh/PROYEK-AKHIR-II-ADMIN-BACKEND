<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'subject_id',
        'classroom_id',
        'assignment_id',
        'score',
        'max_score',
        'type', // assignment, quiz, exam, etc.
        'feedback',
        'semester',
        'academic_year',
        'modified_by_admin',
        'admin_id',
    ];

    // Student who received the grade
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Teacher who gave the grade
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Subject for which the grade was given
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Classroom the student belongs to
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    // Assignment related to this grade (if applicable)
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    // Calculate percentage score
    public function getPercentageAttribute()
    {
        if (!$this->max_score) return 0;
        return round(($this->score / $this->max_score) * 100);
    }

    // Letter grade based on percentage
    public function getLetterGradeAttribute()
    {
        $percentage = $this->percentage;
        
        return match(true) {
            $percentage >= 90 => 'A',
            $percentage >= 80 => 'B',
            $percentage >= 70 => 'C',
            $percentage >= 60 => 'D',
            default => 'E',
        };
    }

    // Admin who modified this grade (if applicable)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
