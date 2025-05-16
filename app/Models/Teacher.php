<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nip',
        'nama', // Assuming this might be the actual column name
        'email',
        'address',
        'phone',
        // other fields...
    ];

    /**
     * Get the name attribute.
     * This accessor helps display a name regardless of database structure
     */
    public function getNameAttribute($value)
    {
        // If name exists and is not empty, use it
        if (!empty($value)) {
            return $value;
        }
        
        // Try different common name field variations
        $nameFields = ['nama', 'full_name', 'fullname', 'first_name'];
        
        foreach ($nameFields as $field) {
            if (isset($this->attributes[$field]) && !empty($this->attributes[$field])) {
                return $this->attributes[$field];
            }
        }
        
        // Combine first and last name if they exist
        if (isset($this->attributes['first_name'])) {
            $name = $this->attributes['first_name'];
            if (isset($this->attributes['last_name'])) {
                $name .= ' ' . $this->attributes['last_name'];
            }
            return $name;
        }
        
        // Use NIP if available as fallback
        if (isset($this->attributes['nip']) && !empty($this->attributes['nip'])) {
            return 'Guru #' . $this->attributes['nip'];
        }
        
        // Last resort: use ID
        return 'Guru ID-' . $this->id;
    }

    /**
     * Schedules taught by this teacher.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    
    /**
     * Subjects this teacher can teach.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher');
    }
    
    /**
     * Related user account (if applicable).
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }    /**
     * Scope a query to only include records for a specific teacher.
     * Can be filtered by teacher ID or user ID depending on the parameter provided.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int|null  $teacherIdOrUserId  Teacher ID or User ID to filter by
     * @param  string  $filterType  'teacher_id' or 'user_id' to specify the filter type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForTeacher(Builder $query, $teacherIdOrUserId = null, $filterType = 'teacher_id')
    {
        // If no ID is provided, try to get the current teacher ID from authenticated user
        if ($teacherIdOrUserId === null && $filterType === 'teacher_id') {
            $teacherIdOrUserId = auth()->user()->teacher->id ?? null;
        }
        
        if ($teacherIdOrUserId) {
            if ($filterType === 'user_id') {
                return $query->where('user_id', $teacherIdOrUserId);
            } else {
                return $query->where('id', $teacherIdOrUserId);
            }
        }
        
        return $query;
    }
}
