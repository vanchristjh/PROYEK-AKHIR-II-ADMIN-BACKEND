<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'attendance_id',
        'student_id',
        'status', // present, absent, late, excused
        'notes',
    ];

    /**
     * Get the attendance this record belongs to
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    /**
     * Get the student this attendance record belongs to
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
