<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentGrade extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'grade_item_id',
        'student_id',
        'score',
        'feedback',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'score' => 'decimal:2',
    ];

    /**
     * Get the student associated with this grade.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the grade item associated with this student grade.
     */
    public function gradeItem(): BelongsTo
    {
        return $this->belongsTo(GradeItem::class);
    }

    /**
     * Get the percentage score.
     */
    public function getPercentageAttribute()
    {
        if (!$this->gradeItem || $this->gradeItem->max_score == 0) {
            return 0;
        }
        
        return min(100, round(($this->score / $this->gradeItem->max_score) * 100));
    }

    /**
     * Get the letter grade based on the percentage score.
     */
    public function getLetterGradeAttribute()
    {
        $percentage = $this->getPercentageAttribute();
        
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'E';
    }
}
