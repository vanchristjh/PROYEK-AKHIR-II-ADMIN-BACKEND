<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'subject_id',
        'date',
        'start_time',
        'end_time',
        'notes',
        'is_completed',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'is_completed' => 'boolean',
    ];

    /**
     * Get the class that this attendance session belongs to.
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    /**
     * Get the subject that this attendance session belongs to.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Get the user who created this attendance session.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the attendance records for this session.
     */
    public function records(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class, 'attendance_id');
    }

    /**
     * Get the status summary of this attendance session.
     */
    public function getStatusSummary(): array
    {
        $summary = [
            'hadir' => 0,
            'izin' => 0,
            'sakit' => 0,
            'alpa' => 0,
            'terlambat' => 0,
            'total' => 0,
        ];

        foreach ($this->records as $record) {
            if (isset($summary[$record->status])) {
                $summary[$record->status]++;
            }
            $summary['total']++;
        }

        return $summary;
    }
}
