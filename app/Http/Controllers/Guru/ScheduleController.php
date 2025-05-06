<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display the teacher's teaching schedule
     */
    public function index()
    {
        $teacher = Auth::user();
        
        // Get all subjects taught by this teacher
        $subjectIds = $teacher->teacherSubjects()->pluck('id');
        
        if ($subjectIds->isEmpty()) {
            return view('guru.schedule.index', [
                'schedules' => [],
                'message' => 'Anda belum ditugaskan untuk mengajar mata pelajaran apapun.'
            ]);
        }
        
        // Get all schedules for these subjects where the teacher is assigned
        $schedules = Schedule::where('teacher_id', $teacher->id)
            ->with('subject', 'classroom')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
            
        // Group schedules by day
        $schedulesByDay = [];
        foreach (Schedule::getDaysOfWeek() as $day => $dayName) {
            $schedulesByDay[$day] = $schedules->where('day_of_week', $day)->values();
        }
        
        return view('guru.schedule.index', [
            'schedulesByDay' => $schedulesByDay,
            'dayNames' => Schedule::getDaysOfWeek()
        ]);
    }
}