<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'status',
        'published_at',
        'expired_at',
        'target_audience',
        'priority',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('expired_at')
                    ->orWhere('expired_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'published' => '<span class="badge bg-success">Dipublikasikan</span>',
            'draft' => '<span class="badge bg-warning text-dark">Draft</span>',
            'archived' => '<span class="badge bg-secondary">Diarsipkan</span>',
            default => '<span class="badge bg-info">Status Lain</span>',
        };
    }

    public function getPriorityBadgeAttribute()
    {
        return match($this->priority) {
            'high' => '<span class="badge bg-danger">Penting</span>',
            'medium' => '<span class="badge bg-info">Sedang</span>',
            'low' => '<span class="badge bg-light text-dark">Rendah</span>',
            default => '',
        };
    }

    public function getIsActiveAttribute()
    {
        if ($this->status !== 'published') {
            return false;
        }

        if ($this->expired_at && $this->expired_at < now()) {
            return false;
        }

        return $this->published_at <= now();
    }
}
