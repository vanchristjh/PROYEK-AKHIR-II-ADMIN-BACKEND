<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_important' => 'boolean',
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
            case 'academic':
                return '<span class="badge bg-primary">Akademik</span>';
            case 'exam':
                return '<span class="badge bg-danger">Ujian</span>';
            case 'holiday':
                return '<span class="badge bg-success">Libur</span>';
            case 'meeting':
                return '<span class="badge bg-info">Rapat</span>';
            case 'extracurricular':
                return '<span class="badge bg-warning text-dark">Ekstrakurikuler</span>';
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
}
