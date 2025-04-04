<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        // Common fields
        'phone_number',  // Changed from 'phone' to 'phone_number' to match database schema
        'address',
        'birth_date',
        'gender',
        'profile_photo',
        // Student specific fields
        'nis',
        'nisn',
        'class_id',  // Changed from 'class' to match relation with ClassRoom
        'academic_year',
        'parent_name',
        'parent_phone',
        // Teacher specific fields
        'nip',
        'nuptk',
        'subject',
        'position',
        'join_date',
        'education_level',
        'education_institution',
        'preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'join_date' => 'date',
            'preferences' => 'array',
        ];
    }
    
    /**
     * Get the profile photo URL attribute.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return url('storage/' . $this->profile_photo);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0066b3&color=fff&bold=true&size=200';
    }
    
    /**
     * Append virtual attributes to JSON output
     */
    protected $appends = ['profile_photo_url'];

    /**
     * Get the student's class.
     */
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }
    
    /**
     * Get the class that the student belongs to.
     */
    public function studentClass()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    /**
     * Get the class that the teacher is homeroom teacher of.
     */
    public function homeroomClass()
    {
        return $this->hasOne(ClassRoom::class, 'teacher_id');
    }

    /**
     * Get the schedules associated with the teacher.
     */
    public function schedules()
    {
        return $this->hasMany(ClassSchedule::class, 'teacher_id');
    }

    /**
     * Get the subjects associated with the teacher.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher', 'teacher_id', 'subject_id')
                    ->withTimestamps();
    }
    
    /**
     * Normalize gender value when setting
     * 
     * @param string $value
     * @return void
     */
    public function setGenderAttribute($value)
    {
        // Normalize gender to single character or enum value based on your DB schema
        if ($value === 'male' || $value === 'laki-laki') {
            $this->attributes['gender'] = 'L'; // Store as 'L' for male
        } elseif ($value === 'female' || $value === 'perempuan') {
            $this->attributes['gender'] = 'P'; // Store as 'P' for female
        } else {
            $this->attributes['gender'] = $value; // Keep as is for other values
        }
    }
    
    /**
     * Get gender in readable format
     * 
     * @return string
     */
    public function getReadableGenderAttribute()
    {
        if ($this->gender === 'L') {
            return 'Laki-laki';
        } elseif ($this->gender === 'P') {
            return 'Perempuan';
        }
        
        return $this->gender;
    }
}
