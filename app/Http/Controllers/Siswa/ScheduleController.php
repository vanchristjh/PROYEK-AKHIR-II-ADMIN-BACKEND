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
                           ->orderBy('day')
                           ->orderBy('start_time')
                           ->get();
        
        // Group schedules by day
        $schedulesByDay = $schedules->groupBy('day');
        
        // Define days for proper ordering
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        return view('siswa.schedule.index', compact('schedulesByDay', 'days'));
    }
}
