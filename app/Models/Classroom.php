<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'grade_level',
        'academic_year',
        'homeroom_teacher_id',
        'capacity',
        'room_number',
    ];

    /**
     * Get the students in this class
     */
    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'classroom_id')->where('role_id', 3); // 3 = siswa role_id
    }

    /**
     * Get the subjects taught in this class
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'classroom_subject');
    }

    /**
     * Get the homeroom teacher
     */
    public function homeroomTeacher()
    {
        return $this->belongsTo(User::class, 'homeroom_teacher_id');
    }

    /**
     * Get the schedule entries for this class
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get the assignments for this classroom
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get the attendances for this classroom
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
