<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category_id',
        'subject_id',
        'class_id',
        'max_score',
        'date',
        'description',
        'academic_year',
        'semester',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the category that the grade item belongs to.
     */
    public function category()
    {
        return $this->belongsTo(GradeCategory::class, 'category_id');
    }

    /**
     * Get the subject that the grade item belongs to.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the class that the grade item belongs to.
     */
    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    /**
     * Get the student grades for this grade item.
     */
    public function studentGrades()
    {
        return $this->hasMany(StudentGrade::class);
    }
}
