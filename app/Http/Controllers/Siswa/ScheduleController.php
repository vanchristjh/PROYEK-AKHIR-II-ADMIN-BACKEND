<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    /**
     * Display the student's class schedule.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->classroom) {
            return view('siswa.schedule.index', [
                'message' => 'You are not assigned to any classroom yet.'
            ]);
        }
        
        $schedules = Schedule::where('classroom_id', $user->classroom_id)
                           ->with(['subject', 'teacher'])
                           ->orderBy('day_of_week') // Changed 'day' to 'day_of_week'
                           ->orderBy('start_time')
                           ->get();
        
        // Group schedules by day
        $schedulesByDay = $schedules->groupBy('day_of_week'); // Changed 'day' to 'day_of_week'
        
        // Define days for proper ordering
        $dayNames = Schedule::getDaysOfWeek(); // Changed from $days to $dayNames and used Schedule model
        
        return view('siswa.schedule.index', compact('schedulesByDay', 'dayNames')); // Changed 'days' to 'dayNames'
    }
}
