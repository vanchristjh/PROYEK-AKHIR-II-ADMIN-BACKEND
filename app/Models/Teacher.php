<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'nip',
        'specialization',
    ];

    /**
     * Get the user associated with the teacher.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the classrooms that this teacher is responsible for (as homeroom teacher).
     */
    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    /**
     * Get the subjects taught by this teacher.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher');
    }
}
