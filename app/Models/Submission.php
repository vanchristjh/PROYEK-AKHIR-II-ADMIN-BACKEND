<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Submission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'assignment_id',
        'student_id',
        'file_path',
        'submitted_at',
        'score',
        'feedback',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    /**
     * Get the assignment that owns the submission.
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Get the student that owns the submission.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Check if the submission is graded.
     *
     * @return bool
     */
    public function isGraded()
    {
        return $this->score !== null;
    }

    /**
     * Check if the submission is late.
     *
     * @return bool
     */
    public function isLate()
    {
        if (!$this->submitted_at || !$this->assignment) {
            return false;
        }
        
        return $this->submitted_at->gt($this->assignment->deadline);
    }
}
