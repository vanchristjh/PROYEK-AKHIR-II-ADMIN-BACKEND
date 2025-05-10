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
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentModel;

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
        'preferences',
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
        'preferences' => 'array',
    ];

    /**
     * Get the user's theme preference.
     * 
     * @return string
     */
    public function getThemePreference()
    {
        return $this->preferences['theme'] ?? 'light';
    }
    
    /**
     * Set the user's theme preference.
     * 
     * @param string $theme
     * @return void
     */
    public function setThemePreference($theme)
    {
        $preferences = $this->preferences ?? [];
        $preferences['theme'] = $theme;
        $this->preferences = $preferences;
        $this->save();
    }

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
        // Check if the role relationship exists first
        if (!$this->role) {
            return false;
        }
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
     * This is now an alias for teacherSubjects() for backward compatibility
     */
    public function subjects()
    {
        return $this->teacherSubjects();
    }

    /**
     * Get all subjects that this teacher teaches through the teacher relationship
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function teacherSubjects()
    {
        // Return a proper relationship instance using hasManyThrough
        return $this->hasManyThrough(
            Subject::class,
            SubjectTeacher::class,
            'teacher_id', // Foreign key on subject_teacher table
            'id',         // Foreign key on subjects table
            'teacher.id', // Local key on users table (via teacher relation)
            'subject_id'  // Local key on subject_teacher table
        )->with('teachers');
    }
    
    /**
     * Get all classrooms that this teacher teaches in
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teachingClassrooms()
    {
        // First, get the teacher record associated with this user
        $teacher = $this->teacher;
        
        // If no teacher record, return empty relationship
        if (!$teacher) {
            // Specify the relationship with proper pivot table to avoid 'classroom_user' error
            return $this->belongsToMany(
                Classroom::class,
                'classroom_subject',  // Use the correct pivot table name
                'subject_id',         // Foreign key
                'classroom_id'        // Related key
            )->whereRaw('1 = 0');     // This creates an empty result set
        }
        
        // Teachers have a many-to-many relationship with classrooms through subjects
        return $this->belongsToMany(
            Classroom::class,      // Related model
            'classroom_subject',   // Pivot table
            'subject_id',          // Foreign key on pivot table
            'classroom_id',        // Related key on pivot table
            null,                  // Local key (will use a subquery instead)
            'id'                   // Related model key
        )->wherePivotIn('subject_id', function($query) use ($teacher) {
            // Subquery to get the subjects this teacher teaches
            $query->select('subject_id')
                  ->from('subject_teacher')
                  ->where('teacher_id', $teacher->id);  // Using teacher_id instead of user_id
        });
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
     * Note: This relationship is for future implementation. Currently the column doesn't exist.
     */
    public function homeroomOf()
    {
        // This feature isn't implemented yet in the database schema
        // When implemented, the classrooms table will need a homeroom_teacher_id column
        return null;
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
        return $this->hasMany(Attendance::class, 'recorded_by');
    }
    
    /**
     * Get attendance records for a student
     */
    public function studentAttendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'student_id');
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

    /**
     * Get the student record associated with the user.
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get the teacher record associated with the user.
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * Get the parent record associated with the user.
     */
    public function parent()
    {
        return $this->hasOne(ParentModel::class);
    }
}
