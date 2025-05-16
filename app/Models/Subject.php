<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    /**
     * The teachers that belong to the subject.
     */
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'subject_teacher', 'subject_id', 'teacher_id');
    }

    /**
     * Get the classrooms where this subject is taught.
     */
    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_subject')
            ->withTimestamps();
    }

    /**
     * Get the schedules related to this subject.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
