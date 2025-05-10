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
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    /**
     * Get the teachers who teach this subject.
     */
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'subject_teacher');
    }

    /**
     * Get the classrooms associated with this subject.
     */
    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_subject');
    }

    /**
     * Get the assignments for this subject.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
