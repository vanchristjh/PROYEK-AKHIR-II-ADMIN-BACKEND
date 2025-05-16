<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Classroom;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'role_id',
        'nisn',
        'nip',
        'phone',
        'address',
        'gender',
        'birth_date',
        'birth_place',
        'avatar',
        'status',
        'classroom_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        // If you have a direct relationship with a single role
        return $this->belongsTo(Role::class);
        
        // If you have a many-to-many relationship with roles
        // return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Get the roles associated with the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // Note: If this should be a many-to-many, add this method and use it instead:
    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'role_user');
    // }

    /**
     * Get the classrooms that the student belongs to.
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Get the subjects taught by this teacher.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher', 'teacher_id', 'subject_id');
    }

    /**
     * Get the subjects taught by this teacher.
     * This is an alias for the subjects relation specifically for teachers.
     */
    public function teacherSubjects()
    {
        return $this->subjects();
    }

    /**
     * Get the classrooms that the user teaches.
     */
    public function teachingClassrooms()
    {
        // Looking at your existing relationships, it seems that a teacher
        // might be related to classrooms through schedules instead
        return $this->hasManyThrough(
            Classroom::class,
            Schedule::class,
            'teacher_id', // Foreign key on the schedules table
            'id', // Foreign key on the classrooms table
            'id', // Local key on the users table
            'classroom_id' // Local key on the schedules table
        );
    }

    /**
     * Get the classrooms that this teacher is homeroom teacher of.
     */
    public function homeroomClasses()
    {
        return $this->hasMany(Classroom::class, 'homeroom_teacher_id');
    }

    /**
     * Get the schedules where this user is the teacher.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }

    /**
     * Get the announcements created by this user
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'author_id');
    }

    /**
     * Check if the user has a given role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->role && $this->role->slug === $role;
    }

    /**
     * Check if the user is an administrator
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }

    /**
     * Check if the user is a teacher/guru
     *
     * @return bool
     */
    public function isGuru()
    {
        return $this->role->name === 'guru';
    }

    /**
     * Check if the user is a student
     *
     * @return bool
     */
    public function isSiswa()
    {
        return $this->role->name === 'siswa';
    }

    /**
     * Get the user's ID number based on their role.
     *
     * @return string|null
     */
    public function getIdNumberAttribute()
    {
        if ($this->role_id == 2) { // Teacher role
            return $this->nip;
        } elseif ($this->role_id == 3) { // Student role
            return $this->nisn;
        }
        return null; // For admin or other roles
    }

    /**
     * Set the user's ID number based on their role.
     *
     * @param string|null $value
     * @return void
     */
    public function setIdNumberAttribute($value)
    {
        if ($this->role_id == 2) { // Teacher role
            $this->attributes['nip'] = $value;
            $this->attributes['nisn'] = null;
        } elseif ($this->role_id == 3) { // Student role
            $this->attributes['nisn'] = $value;
            $this->attributes['nip'] = null;
        }
        // For admin or other roles, we don't set any ID number
    }
}
