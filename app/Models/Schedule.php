<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'teacher_id',
        'classroom_id',
        'day',
        'start_time',
        'end_time',
        'room',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'day' => 'integer',
        'start_time' => 'string',
        'end_time' => 'string',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_time',
        'isOngoing',
        'isUpcoming',
        'isPast'
    ];

    /**
     * Get the subject of this schedule
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the teacher for this schedule
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the classroom for this schedule
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Get formatted time (start - end)
     * 
     * @return string
     */
    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->start_time)->format('H:i') . ' - ' . 
               Carbon::parse($this->end_time)->format('H:i');
    }

    /**
     * Check if this schedule is currently ongoing
     * 
     * @return bool
     */
    public function getIsOngoingAttribute()
    {
        $now = Carbon::now();
        $currentTime = $now->format('H:i:s');
        $currentDay = $this->getDayNumberFromName($now->dayOfWeek);

        return $this->day == $currentDay && 
               $currentTime >= $this->start_time && 
               $currentTime <= $this->end_time;
    }

    /**
     * Check if this schedule is upcoming today
     * 
     * @return bool
     */
    public function getIsUpcomingAttribute()
    {
        $now = Carbon::now();
        $currentTime = $now->format('H:i:s');
        $currentDay = $this->getDayNumberFromName($now->dayOfWeek);

        return $this->day == $currentDay && $currentTime < $this->start_time;
    }

    /**
     * Check if this schedule is already past today
     * 
     * @return bool
     */
    public function getIsPastAttribute()
    {
        $now = Carbon::now();
        $currentTime = $now->format('H:i:s');
        $currentDay = $this->getDayNumberFromName($now->dayOfWeek);

        return $this->day == $currentDay && $currentTime > $this->end_time;
    }

    /**
     * Convert day name to day number (1-7)
     * 
     * @param int $carbonDayOfWeek
     * @return int
     */
    private function getDayNumberFromName($carbonDayOfWeek)
    {
        // Convert Carbon's day of week (0 = Sunday, 6 = Saturday) 
        // to our system's (1 = Monday, 7 = Sunday)
        return $carbonDayOfWeek == 0 ? 7 : $carbonDayOfWeek;
    }

    /**
     * Get day name from day number
     * 
     * @return string
     */
    public function getDayNameAttribute()
    {
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];

        return $days[$this->day] ?? '';
    }

    /**
     * Get schedules for a specific day
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $day Day number (1-7, 1 = Monday)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForDay($query, $day)
    {
        return $query->where('day', $day);
    }

    /**
     * Get schedules for a specific teacher
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $teacherId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }    /**
     * Get schedules for a specific classroom
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $classroomId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForClassroom($query, $classroomId)
    {
        return $query->where('classroom_id', $classroomId);
    }
    
    /**
     * Get weekly classroom schedule organized by day
     * 
     * @param int $classroomId
     * @return array
     */
    public static function getClassroomWeeklySchedule($classroomId)
    {
        $schedules = self::with(['subject', 'teacher'])
            ->where('classroom_id', $classroomId)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();
            
        $dayNames = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];
        
        // Group schedules by day
        $schedulesByDay = [];
        foreach ($dayNames as $dayNumber => $dayName) {
            $schedulesByDay[$dayNumber] = $schedules->filter(function($schedule) use ($dayNumber) {
                return $schedule->day == $dayNumber;
            })->values()->all();
        }
        
        return $schedulesByDay;
    }
    
    /**
     * Get weekly teacher schedule organized by day
     * 
     * @param int $teacherId
     * @return array
     */
    public static function getTeacherWeeklySchedule($teacherId)
    {
        $schedules = self::with(['subject', 'classroom'])
            ->where('teacher_id', $teacherId)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();
            
        $dayNames = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];
        
        // Group schedules by day
        $schedulesByDay = [];
        foreach ($dayNames as $dayNumber => $dayName) {
            $schedulesByDay[$dayNumber] = $schedules->filter(function($schedule) use ($dayNumber) {
                return $schedule->day == $dayNumber;
            })->values()->all();
        }
        
        return $schedulesByDay;
    }
    
    /**
     * Get today's schedule for a classroom
     * 
     * @param int $classroomId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTodayClassroomSchedule($classroomId)
    {
        $today = self::getDayOfWeekNumber();
        
        return self::with(['subject', 'teacher'])
            ->where('classroom_id', $classroomId)
            ->where('day', $today)
            ->orderBy('start_time')
            ->get();
    }
    
    /**
     * Get today's schedule for a teacher
     * 
     * @param int $teacherId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTodayTeacherSchedule($teacherId)
    {
        $today = self::getDayOfWeekNumber();
        
        return self::with(['subject', 'classroom'])
            ->where('teacher_id', $teacherId)
            ->where('day', $today)
            ->orderBy('start_time')
            ->get();
    }
    
    /**
     * Get current day of week as a number (1-7, 1 = Monday)
     * 
     * @return int
     */
    public static function getDayOfWeekNumber()
    {
        $dayOfWeek = Carbon::now()->dayOfWeek;
        // Convert Carbon's day of week (0 = Sunday, 6 = Saturday) 
        // to our system's (1 = Monday, 7 = Sunday)
        return $dayOfWeek == 0 ? 7 : $dayOfWeek;
    }
}
