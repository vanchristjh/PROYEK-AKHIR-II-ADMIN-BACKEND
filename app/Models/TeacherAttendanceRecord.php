<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherAttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_attendance_id',
        'teacher_id',
        'status',
        'notes',
        'photo',
        'check_in_time',
        'check_out_time',
        'location',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    /**
     * Get the attendance session this record belongs to
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(TeacherAttendance::class, 'teacher_attendance_id');
    }

    /**
     * Get the teacher this record belongs to
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the photo URL
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        
        return $this->teacher?->profile_photo_url ?? 
               'https://ui-avatars.com/api/?name=Teacher&background=2d4059&color=fff&size=200';
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
