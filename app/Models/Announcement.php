<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'author_id',
        'is_important',
        'audience',
        'publish_date',
        'expiry_date',
        'attachment'
    ];

    protected $casts = [
        'is_important' => 'boolean',
        'publish_date' => 'datetime',
        'expiry_date' => 'datetime',
    ];

    /**
     * Get the author of the announcement
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Check if the announcement is new (published within the last 3 days)
     */
    public function isNew()
    {
        return $this->publish_date->diffInDays(now()) <= 3;
    }

    /**
     * Get an excerpt of the content
     */
    public function excerpt($length = 150)
    {
        return strlen($this->content) > $length 
            ? substr($this->content, 0, $length) . '...' 
            : $this->content;
    }

    /**
     * Determine if the announcement has an attachment
     */
    public function hasAttachment()
    {
        return !empty($this->attachment);
    }

    /**
     * Get the full URL of the attachment
     */
    public function getAttachmentUrl()
    {
        if (!$this->hasAttachment()) {
            return null;
        }
        
        return asset('storage/' . $this->attachment);
    }

    /**
     * Handle file upload for the announcement attachment
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @return string The path to the stored file
     */
    public static function handleAttachmentUpload($file)
    {
        if (!$file) {
            return null;
        }
        
        return $file->store('announcements', 'public');
    }

    /**
     * Get the attachment's filename
     */
    public function getAttachmentName()
    {
        if (!$this->hasAttachment()) {
            return null;
        }
        
        return basename($this->attachment);
    }

    /**
     * Scope a query to only include published announcements.
     */
    public function scopePublished($query)
    {
        return $query->where('publish_date', '<=', Carbon::now());
    }

    /**
     * Scope a query to only include non-expired announcements.
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expiry_date')
              ->orWhere('expiry_date', '>', Carbon::now());
        });
    }

    /**
     * Scope a query to only include announcements for a specific audience.
     */
    public function scopeForAudience($query, $audience)
    {
        if (is_array($audience)) {
            return $query->where('audience', 'all')
                         ->orWhereIn('audience', $audience);
        }
        
        return $query->where('audience', 'all')
                     ->orWhere('audience', $audience);
    }
}
