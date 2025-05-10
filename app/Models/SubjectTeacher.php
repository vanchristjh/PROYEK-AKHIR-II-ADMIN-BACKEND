<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SubjectTeacher extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subject_teacher';
    
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject_id',
        'teacher_id',
    ];
    
    /**
     * Get the subject that owns this relationship.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    
    /**
     * Get the teacher that owns this relationship.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Get the classrooms this subject is taught in.
     */
    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_subject', 'subject_id', 'classroom_id', 'subject_id');
    }
}
