<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'author_id',
        'audience',
        'is_important',
        'publish_date',
        'expiry_date',
        'attachment',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_important' => 'boolean',
        'publish_date' => 'datetime',
        'expiry_date' => 'datetime',
    ];

    /**
     * Get the author that created the announcement.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope a query to only include announcements that are published.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('publish_date', '<=', now());
    }

    /**
     * Scope a query to only include announcements for specific audience.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $audience
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForAudience($query, $audience)
    {
        return $query->where(function($q) use ($audience) {
            $q->where('audience', 'all')
              ->orWhere('audience', $audience);
        });
    }
    
    /**
     * Scope a query to only include announcements visible to a specific role.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisibleToRole($query, $role)
    {
        return $query->where(function ($query) use ($role) {
            $query->where('audience', 'all')
                ->orWhere(function ($query) use ($role) {
                    if ($role === 'admin') {
                        // Admins can see all announcements
                        return $query;
                    } elseif ($role === 'guru') {
                        // Teachers can see announcements for teachers and all
                        $query->orWhere('audience', 'teachers');
                    } elseif ($role === 'siswa') {
                        // Students can see announcements for students and all
                        $query->orWhere('audience', 'students');
                    }
                });
        });
    }
    
    /**
     * Scope a query to only include active announcements (published and not expired).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('publish_date', '<=', now())
                     ->where(function($query) {
                         $query->whereNull('expiry_date')
                               ->orWhere('expiry_date', '>=', now());
                     });
    }
    
    /**
     * Check if the announcement is published.
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->publish_date->isPast() || $this->publish_date->isCurrentDay();
    }

    /**
     * Check if the announcement is a draft.
     *
     * @return bool
     */
    public function isDraft()
    {
        return $this->publish_date->isFuture();
    }
    
    /**
     * Get the attachment path using the appropriate column name
     * 
     * @return string|null
     */
    public function getAttachmentAttribute()
    {
        // If the model has attachment attribute directly, return it
        if (array_key_exists('attachment', $this->attributes)) {
            return $this->attributes['attachment'];
        }
        
        // Otherwise check for attachment_path
        if (array_key_exists('attachment_path', $this->attributes)) {
            return $this->attributes['attachment_path'];
        }
        
        return null;
    }

    /**
     * Get the attachment path 
     * 
     * @return string|null
     */
    public function getAttachmentPathAttribute()
    {
        return $this->attachment;
    }

    /**
     * Get file extension of attachment
     *
     * @return string|null
     */
    public function getFileExtensionAttribute()
    {
        if (!$this->attachment) {
            return null;
        }
        
        return pathinfo($this->attachment, PATHINFO_EXTENSION);
    }

    /**
     * Get the file type icon class based on extension
     *
     * @return string
     */
    public function getFileIconAttribute()
    {
        if (!$this->attachment) {
            return 'fa-file';
        }
        
        $extension = strtolower($this->file_extension);
        
        return match($extension) {
            'pdf' => 'fa-file-pdf',
            'doc', 'docx' => 'fa-file-word',
            'xls', 'xlsx' => 'fa-file-excel',
            'ppt', 'pptx' => 'fa-file-powerpoint',
            'jpg', 'jpeg', 'png', 'gif' => 'fa-file-image',
            'zip', 'rar' => 'fa-file-archive',
            default => 'fa-file'
        };
    }
}
