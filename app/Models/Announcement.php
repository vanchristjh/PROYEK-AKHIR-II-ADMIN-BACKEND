<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    /**
     * Get the user who created this announcement
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include active announcements
     */
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

    /**
     * Get the HTML badge for status
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'published' => '<span class="badge bg-success">Dipublikasikan</span>',
            'draft' => '<span class="badge bg-warning text-dark">Draft</span>',
            'archived' => '<span class="badge bg-secondary">Diarsipkan</span>',
            default => '<span class="badge bg-info">Status Lain</span>',
        };
    }

    /**
     * Get the HTML badge for priority
     */
    public function getPriorityBadgeAttribute()
    {
        return match($this->priority) {
            'high' => '<span class="badge bg-danger">Penting</span>',
            'medium' => '<span class="badge bg-info">Sedang</span>',
            'low' => '<span class="badge bg-light text-dark">Rendah</span>',
            default => '',
        };
    }

    /**
     * Check if the announcement is currently active/visible
     */
    public function getIsActiveAttribute()
    {
        if ($this->status !== 'published') {
            return false;
        }

        if ($this->expired_at && $this->expired_at < now()) {
            return false;
        }

        if ($this->published_at && $this->published_at > now()) {
            return false;
        }

        return true;
    }
    
    /**
     * Get the plain text excerpt of the content
     */
    public function getExcerptAttribute($length = 100)
    {
        return Str::limit(strip_tags($this->content), $length);
    }

    /**
     * Get the formatted published date
     */
    public function getFormattedPublishedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('d M Y H:i') : '-';
    }

    /**
     * Get the formatted expired date
     */
    public function getFormattedExpiredDateAttribute()
    {
        return $this->expired_at ? $this->expired_at->format('d M Y H:i') : 'Tidak ada batas';
    }
}
