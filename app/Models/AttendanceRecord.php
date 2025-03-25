<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'student_id',
        'status',
        'notes'
    ];

    /**
     * Valid attendance statuses
     */
    public const STATUSES = [
        'hadir',
        'izin',
        'sakit',
        'alpa',
        'terlambat'
    ];

    /**
     * Get the attendance session that this record belongs to.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }

    /**
     * Get the student that this attendance record is for.
     */
    public function student(): BelongsTo
    {
        // Explicitly target the users table for students
        return $this->belongsTo(User::class, 'student_id')->where('role', 'student');
    }

    /**
     * Get the user-friendly status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'hadir' => 'Hadir',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'alpa' => 'Alpa',
            'terlambat' => 'Terlambat',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get the status badge CSS class.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'hadir' => 'bg-success',
            'izin' => 'bg-warning text-dark',
            'sakit' => 'bg-info',
            'alpa' => 'bg-danger',
            'terlambat' => 'bg-secondary',
            default => 'bg-light text-dark',
        };
    }
}
