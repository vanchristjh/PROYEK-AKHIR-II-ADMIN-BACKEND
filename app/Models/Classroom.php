<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'grade_level',
        'academic_year',
        'homeroom_teacher_id',
        'capacity',
        'room_number',
    ];

    /**
     * Get the homeroom teacher for the classroom.
     */
    public function homeroomTeacher()
    {
        return $this->belongsTo(User::class, 'homeroom_teacher_id');
    }

    /**
     * Get the students for the classroom.
     */
    public function students()
    {
        return $this->hasMany(User::class, 'classroom_id');
    }
    
    /**
     * Get the schedules for this classroom.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get the subjects for this classroom.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'classroom_subject');
    }
}
