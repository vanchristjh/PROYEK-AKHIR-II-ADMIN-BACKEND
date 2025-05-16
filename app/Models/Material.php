<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'subject_id',
        'teacher_id',
        'publish_date',
        'is_active'
    ];

    protected $casts = [
        'publish_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the subject that the material belongs to.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher who created the material.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Check if the material is new (less than 7 days old).
     */
    public function isNew()
    {
        return $this->publish_date->diffInDays(Carbon::now()) < 7;
    }

    /**
     * Get file type based on file extension.
     */
    public function getFileType()
    {
        if (empty($this->file_path)) {
            return 'Unknown File';
        }

        $extension = pathinfo($this->file_path, PATHINFO_EXTENSION);
        
        switch (strtolower($extension)) {
            case 'pdf':
                return 'PDF Document';
            case 'doc':
            case 'docx':
                return 'Word Document';
            case 'ppt':
            case 'pptx':
                return 'PowerPoint Presentation';
            case 'xls':
            case 'xlsx':
                return 'Excel Spreadsheet';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return 'Image File';
            case 'mp4':
            case 'avi':
            case 'mov':
                return 'Video File';
            case 'mp3':
            case 'wav':
                return 'Audio File';
            case 'zip':
            case 'rar':
                return 'Compressed Archive';
            default:
                return 'File';
        }
    }

    /**
     * Get short file type for display in badges
     * 
     * @return string
     */
    public function getFileTypeShort()
    {
        if (empty($this->file_path)) {
            return 'TXT';
        }
        
        $extension = pathinfo($this->file_path, PATHINFO_EXTENSION);
        return strtoupper($extension);
    }

    /**
     * Get file extension
     * 
     * @return string
     */
    public function getFileExtensionAttribute()
    {
        if (empty($this->file_path)) {
            return '';
        }
        
        return pathinfo($this->file_path, PATHINFO_EXTENSION);
    }

    /**
     * Get CSS class for file icon
     * 
     * @return string
     */
    public function getFileIconAttribute()
    {
        if (empty($this->file_path)) {
            return 'fa-file-alt';
        }
        
        $extension = strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'pdf':
                return 'fa-file-pdf';
            case 'doc':
            case 'docx':
                return 'fa-file-word';
            case 'xls':
            case 'xlsx':
                return 'fa-file-excel';
            case 'ppt':
            case 'pptx':
                return 'fa-file-powerpoint';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return 'fa-file-image';
            case 'mp4':
            case 'avi':
            case 'mov':
                return 'fa-file-video';
            case 'mp3':
            case 'wav':
                return 'fa-file-audio';
            case 'zip':
            case 'rar':
                return 'fa-file-archive';
            default:
                return 'fa-file-alt';
        }
    }

    /**
     * Get CSS color class for file icon
     * 
     * @return string
     */
    public function getFileColorAttribute()
    {
        if (empty($this->file_path)) {
            return 'text-gray-500';
        }
        
        $extension = strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'pdf':
                return 'text-red-600';
            case 'doc':
            case 'docx':
                return 'text-blue-600';
            case 'xls':
            case 'xlsx':
                return 'text-green-600';
            case 'ppt':
            case 'pptx':
                return 'text-orange-600';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return 'text-purple-600';
            case 'mp4':
            case 'avi':
            case 'mov':
                return 'text-pink-600';
            case 'mp3':
            case 'wav':
                return 'text-indigo-600';
            case 'zip':
            case 'rar':
                return 'text-yellow-600';
            default:
                return 'text-gray-600';
        }
    }

    /**
     * Get full file URL
     */
    public function getFileUrlAttribute()
    {
        if (!$this->file_path) {
            return null;
        }
        
        return Storage::url($this->file_path);
    }
}
