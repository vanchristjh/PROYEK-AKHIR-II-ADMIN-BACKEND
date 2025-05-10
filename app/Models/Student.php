<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'nis',
        'classroom_id',
        'parent_id',
    ];

    /**
     * Get the user that owns the student.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the classroom that the student belongs to.
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Get the parent of the student.
     */
    public function parent()
    {
        // Adjust this relationship based on your existing database structure
        // It might be using a different name than ParentModel
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the submissions for the student.
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
