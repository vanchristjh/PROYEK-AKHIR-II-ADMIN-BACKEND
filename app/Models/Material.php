<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'subject_id',
        'teacher_id',
        'classroom_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Teacher who uploaded the material
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Subject this material belongs to
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Classroom this material is for
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
    
    // File extension attribute
    public function getFileExtensionAttribute()
    {
        if (!$this->file_path) return null;
        return pathinfo($this->file_path, PATHINFO_EXTENSION);
    }
    
    // File icon based on extension
    public function getFileIconAttribute()
    {
        $extension = $this->file_extension;
        if (!$extension) return 'fa-file';
        
        return match(strtolower($extension)) {
            'pdf' => 'fa-file-pdf',
            'doc', 'docx' => 'fa-file-word',
            'xls', 'xlsx' => 'fa-file-excel',
            'ppt', 'pptx' => 'fa-file-powerpoint',
            'jpg', 'jpeg', 'png', 'gif' => 'fa-file-image',
            'zip', 'rar' => 'fa-file-archive',
            'txt' => 'fa-file-alt',
            default => 'fa-file',
        };
    }
    
    // File color based on extension
    public function getFileColorAttribute()
    {
        $extension = $this->file_extension;
        if (!$extension) return 'text-gray-500';
        
        return match(strtolower($extension)) {
            'pdf' => 'text-red-500',
            'doc', 'docx' => 'text-blue-500',
            'xls', 'xlsx' => 'text-green-500',
            'ppt', 'pptx' => 'text-orange-500',
            'jpg', 'jpeg', 'png', 'gif' => 'text-purple-500',
            'zip', 'rar' => 'text-yellow-600',
            default => 'text-gray-500',
        };
    }
}
