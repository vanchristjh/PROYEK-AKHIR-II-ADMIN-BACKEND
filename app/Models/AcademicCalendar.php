<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str; // Make sure this import is present

class AcademicCalendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'event_type',
        'is_important',
        'academic_year',
        'semester',
        'target_audience',
        'created_by',
        'is_holiday',
        'is_exam',
        'color',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_important' => 'boolean',
        'is_holiday' => 'boolean',
        'is_exam' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getDurationAttribute()
    {
        if ($this->start_date->toDateString() == $this->end_date->toDateString()) {
            return $this->start_date->format('d M Y') . ', ' . 
                   $this->start_date->format('H:i') . ' - ' . 
                   $this->end_date->format('H:i');
        }
        
        return $this->start_date->format('d M Y H:i') . ' - ' . 
               $this->end_date->format('d M Y H:i');
    }

    public function getIsOngoingAttribute()
    {
        $now = Carbon::now();
        return $now->between($this->start_date, $this->end_date);
    }

    public function getEventTypeBadgeAttribute()
    {
        switch ($this->event_type) {
            case 'academic': return '<span class="badge bg-primary">Akademik</span>';
            case 'exam': return '<span class="badge bg-danger">Ujian</span>';
            case 'holiday': return '<span class="badge bg-success">Libur</span>';
            case 'meeting': return '<span class="badge bg-info">Rapat</span>';
            case 'extracurricular': return '<span class="badge bg-warning text-dark">Ekstrakurikuler</span>';
            default:
                return '<span class="badge bg-secondary">Lainnya</span>';
        }
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now())
                    ->orderBy('start_date', 'asc');
    }

    public function scopeCurrent($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->orderBy('end_date', 'asc');
    }

    public function scopePast($query)
    {
        return $query->where('end_date', '<', now())
                    ->orderBy('end_date', 'desc');
    }

    public function getColorAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        if ($this->is_holiday) {
            return '#dc3545'; // red
        }
        
        if ($this->is_exam) {
            return '#fd7e14'; // orange
        }
        
        $typeColors = [
            'holiday' => '#dc3545', // red
            'exam' => '#fd7e14', // orange
            'academic' => '#0d6efd', // blue
            'extracurricular' => '#198754', // green
            'meeting' => '#6f42c1', // purple
            'other' => '#6c757d', // gray
        ];
        
        return $typeColors[$this->event_type] ?? '#6c757d';
    }

    public static function createEvent(array $data)
    {
        return self::create($data);
    }
}
