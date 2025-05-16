<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student || !$student->classroom) {
            return view('siswa.schedule.index', [
                'schedules' => [], // Empty array instead of null
                'subjects' => Subject::all(),
                'classroom' => null,
            ]);
        }
        
        $classroom = $student->classroom;
        
        // Get available subjects for the filter
        $subjects = Subject::all();
        
        // Base query
        $query = Schedule::where('classroom_id', $classroom->id)
            ->with(['subject', 'teacher', 'classroom']);
        
        // Apply day filter if provided
        if ($request->has('day') && $request->day) {
            $query->where('day', $request->day);
        }
        
        // Apply subject filter if provided
        if ($request->has('subject_id') && $request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }
        
        // Get schedules and group by day
        $schedules = $query->get()->groupBy('day');
        
        // Create weekly schedule view for printing
        $timeSlots = Schedule::distinct()
            ->where('classroom_id', $classroom->id)
            ->orderBy('start_time')
            ->pluck('start_time')
            ->map(function ($time) {
                return substr($time, 0, 5);
            })
            ->unique()
            ->toArray();
        
        $weeklySchedule = [];
        
        foreach ($schedules as $day => $daySchedules) {
            foreach ($daySchedules as $schedule) {
                $startTime = substr($schedule->start_time, 0, 5);
                $weeklySchedule[$day][$startTime] = $schedule;
            }
        }
        
        return view('siswa.schedule.index', [
            'schedules' => $schedules,
            'subjects' => $subjects,
            'classroom' => $classroom,
            'timeSlots' => $timeSlots,
            'weeklySchedule' => $weeklySchedule,
        ]);
    }

    public function show($id)
    {
        $schedule = Schedule::with(['subject', 'teacher', 'classroom'])->findOrFail($id);
        
        // Check if student belongs to this classroom
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student || $student->classroom_id !== $schedule->classroom_id) {
            return abort(403, 'You do not have access to view this schedule');
        }
        
        return view('siswa.schedule.show', [
            'schedule' => $schedule
        ]);
    }
}
