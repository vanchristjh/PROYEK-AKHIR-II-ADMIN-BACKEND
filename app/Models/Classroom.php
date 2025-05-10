<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'level',
        'year',
        'teacher_id', // homeroom teacher
    ];

    /**
     * Get the students for the classroom.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the homeroom teacher for the classroom.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Get the subjects assigned to this classroom.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'classroom_subject');
    }

    /**
     * Get the assignments for the classroom.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}
