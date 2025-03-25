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
        'name',
        'nip',
        'email',
        'phone_number',
        'gender',
        'birth_date',
        'address',
        'subject',
        'position',
        'photo',
    ];

    /**
     * Get the corresponding user record
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    /**
     * Get the classes that this teacher is in charge of
     */
    public function classes()
    {
        return $this->hasMany(ClassRoom::class, 'teacher_id');
    }

    /**
     * Get all attendance records for this teacher
     */
    public function attendanceRecords()
    {
        return $this->hasMany(TeacherAttendanceRecord::class, 'teacher_id');
    }

    /**
     * Get the subjects taught by this teacher
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher', 'teacher_id', 'subject_id');
    }

    /**
     * Scope to get teachers from users table
     */
    public static function getTeachersFromUsers()
    {
        return User::where('role', 'teacher')->get();
    }
}
