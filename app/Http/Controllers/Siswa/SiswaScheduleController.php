<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaScheduleController extends Controller
{
    /**
     * Display the student's class schedule
     */
    public function index()
    {
        // Get student's classroom
        $classroomId = Auth::user()->classroom_id;
        
        if (!$classroomId) {
            return view('siswa.schedule.index', [
                'schedules' => [],
                'message' => 'You are not assigned to any classroom yet.'
            ]);
        }
        
        // Get all schedules for the classroom
        $schedules = Schedule::where('classroom_id', $classroomId)
            ->with('subject', 'teacher')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
            
        // Group schedules by day
        $schedulesByDay = [];
        foreach (Schedule::getDaysOfWeek() as $day => $dayName) {
            $schedulesByDay[$day] = $schedules->where('day_of_week', $day)->values();
        }
        
        return view('siswa.schedule.index', [
            'schedulesByDay' => $schedulesByDay,
            'dayNames' => Schedule::getDaysOfWeek()
        ]);
    }
}
