<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'status',
        'priority',
        'published_at',
        'expired_at',
        'target_audience',
        'created_by',
        'attachment_path',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    protected $dates = [
        'published_at',
        'expired_at',
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
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>=', now());
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
        switch ($this->priority) {
            case 'high':
                return '<span class="badge bg-danger">Penting</span>';
            case 'medium':
                return '<span class="badge bg-info">Sedang</span>';
            default:
                return '<span class="badge bg-secondary">Rendah</span>';
        }
    }

    /**
     * Check if the announcement is currently active/visible
     */
    public function getIsActiveAttribute()
    {
        if ($this->status !== 'published') {
            return false;
        }

        $now = Carbon::now();

        if ($this->published_at && $this->published_at->gt($now)) {
            return false;
        }

        if ($this->expired_at && $this->expired_at->lt($now)) {
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
