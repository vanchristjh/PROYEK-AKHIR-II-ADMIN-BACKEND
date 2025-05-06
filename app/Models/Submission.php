<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'file_path',
        'notes',
        'score',
        'feedback',
        'submitted_at',
        'graded_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
    ];

    /**
     * Get the assignment this submission belongs to
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Get the student who made this submission
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Check if the submission has been graded
     */
    public function isGraded(): bool
    {
        return !is_null($this->score) && !is_null($this->graded_at);
    }

    /**
     * Check if the submission was made before the deadline
     */
    public function isOnTime(): bool
    {
        return $this->submitted_at->lessThanOrEqualTo($this->assignment->deadline);
    }

    /**
     * Check if the submission was submitted late
     */
    public function isLate(): bool
    {
        $deadline = $this->assignment->deadline;
        return $deadline && $this->submitted_at->greaterThan($deadline);
    }
}
