<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends User
{
    use HasFactory;

    /**
     * Override the table name to use users table
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('role', function ($query) {
            $query->where('role', 'student');
        });
    }

    /**
     * Create a new Eloquent model instance with role set to 'student'.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->attributes['role'] = 'student';
    }
    
    /**
     * Get the classroom that the student belongs to.
     */
    public function classroom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    /**
     * Get the user associated with the student.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attendance records for the student.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    
    /**
     * Get full name with ID.
     */
    public function getFullNameWithIdAttribute()
    {
        return $this->name . ' (' . $this->student_id_number . ')';
    }
    
    /**
     * Get students from users table
     * This static method helps adapt to the user-based student model
     */
    public static function getStudentsFromUsers($classId = null)
    {
        $query = User::where('role', 'student');
        
        if ($classId) {
            $query->where('class_id', $classId);
        }
        
        return $query->orderBy('name')->get();
    }

    /**
     * Get students from users table for a specific class
     */
    public static function getStudentsForClass($classId)
    {
        return User::where('role', 'student')
            ->when($classId, function($query) use ($classId) {
                return $query->where('class_id', $classId);
            })
            ->orderBy('name')
            ->get();
    }

    /**
     * Find a student by ID
     * 
     * @param int $id
     * @return User|null
     */
    public static function findStudent($id)
    {
        return User::where('role', 'student')->find($id);
    }
}
