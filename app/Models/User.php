<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

// Models
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\SubjectTeacher;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Material;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\DiscussionPost;
use App\Models\Comment;
use App\Models\Schedule;
use App\Models\UserNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role_id',
        'avatar',
        'classroom_id',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the role that owns the user
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * For compatibility and to avoid "roles()" method not found error
     */
    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Add a helper method to check roles conveniently
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role->slug === $roleName;
    }

    /**
     * Check if the user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if the user is a teacher
     */
    public function isTeacher(): bool
    {
        return $this->hasRole('guru');
    }
    
    /**
     * Alias for isTeacher() to maintain compatibility
     */
    public function isGuru(): bool
    {
        return $this->isTeacher();
    }

    /**
     * Check if the user is a student
     */
    public function isStudent(): bool
    {
        return $this->hasRole('siswa');
    }

    /**
     * Get the classroom that owns the student
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Get the subjects that this teacher teaches
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher');
    }

    /**
     * Get all subjects that this teacher teaches
     */
    public function teacherSubjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher', 'user_id', 'subject_id');
    }
    
    /**
     * Get all classrooms that this teacher teaches
     */
    public function teachingClassrooms(): HasManyThrough
    {
        return $this->hasManyThrough(
            Classroom::class,
            SubjectTeacher::class,
            'user_id', // Foreign key on SubjectTeacher table
            'id',      // Foreign key on Classroom table
            'id',      // Local key on User table
            'subject_id' // Local key on SubjectTeacher table
        )->distinct();
    }

    /**
     * Get the assignments created by this teacher
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'teacher_id');
    }

    /**
     * Get the submissions made by the user (student).
     */
    public function studentSubmissions()
    {
        return $this->hasMany(Submission::class, 'student_id');
    }
    
    /**
     * Get the classroom that the user (teacher) is homeroom teacher of.
     */
    public function homeroomOf()
    {
        return $this->hasOne(Classroom::class, 'homeroom_teacher_id');
    }
    
    /**
     * Get the materials created by this teacher
     */
    public function materials()
    {
        return $this->hasMany(Material::class, 'teacher_id');
    }
    
    /**
     * Get the announcements authored by this user
     */
    public function authoredAnnouncements()
    {
        return $this->hasMany(Announcement::class, 'author_id');
    }

    /**
     * Get the announcements created by this user.
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'author_id');
    }

    /**
     * Get attendance records created by teacher
     */
    public function attendanceRecords()
    {
        return $this->hasMany(Attendance::class, 'teacher_id');
    }
    
    /**
     * Get attendance records for a student
     */
    public function studentAttendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }
    
    /**
     * Get the grades given by this teacher
     */
    public function givenGrades()
    {
        return $this->hasMany(Grade::class, 'teacher_id');
    }
    
    /**
     * Get the grades received by this student
     */
    public function receivedGrades()
    {
        return $this->hasMany(Grade::class, 'student_id');
    }
    
    /**
     * Get all discussion posts by this user
     */
    public function discussionPosts()
    {
        return $this->hasMany(DiscussionPost::class);
    }
    
    /**
     * Get all comments by this user
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    /**
     * Get schedule for this user (relevant for both teachers and students)
     */
    public function schedule()
    {
        if ($this->isStudent()) {
            return $this->classroom ? $this->classroom->schedules : collect();
        } elseif ($this->isTeacher()) {
            return Schedule::whereHas('subject', function($query) {
                $query->whereHas('teachers', function($q) {
                    $q->where('users.id', $this->id);
                });
            });
        }
        
        return collect();
    }
    
    /**
     * Get notifications for this user
     */
    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }
}
