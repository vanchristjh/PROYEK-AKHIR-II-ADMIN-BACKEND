<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the schedules.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get current logged in student
        $student = Student::where('user_id', Auth::id())->first();
        
        // Make sure we have a classroom, even if it might be null
        $classroom = $student ? $student->classroom : null;
        
        // Build the query
        $query = Schedule::with(['subject', 'teacher'])
                ->where('classroom_id', $classroom ? $classroom->id : null)
                ->orderBy('day')
                ->orderBy('start_time');

        // Apply filters if present
        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }
        
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        
        // Get all schedules
        $allSchedules = $classroom ? $query->get() : collect([]);
        
        // Group by day for better display
        $schedules = $allSchedules->groupBy('day');
        
        // Get subjects for filter dropdown
        $subjects = Subject::whereIn('id', $allSchedules->pluck('subject_id')->unique())->get();
        
        return view('siswa.schedule.index', compact('schedules', 'subjects', 'classroom'));
    }
    
    /**
     * Display schedules for a specific day.
     *
     * @param  string  $day
     * @return \Illuminate\Http\Response
     */
    public function showDay($day)
    {
        // Validate day number
        if (!in_array($day, ['1', '2', '3', '4', '5', '6', '7'])) {
            return redirect()->route('siswa.schedule.index')->withErrors('Hari tidak valid.');
        }
        
        // Convert day number to name
        $dayNames = [
            '1' => 'Senin',
            '2' => 'Selasa', 
            '3' => 'Rabu', 
            '4' => 'Kamis', 
            '5' => 'Jumat', 
            '6' => 'Sabtu',
            '7' => 'Minggu'
        ];
        
        $dayName = $dayNames[$day];
        
        // Get student's classroom
        $student = Student::where('user_id', Auth::id())->first();
        
        // Make sure we have a classroom, even if it might be null
        $classroom = $student ? $student->classroom : null;
        
        // Get schedules for this day
        $schedules = $classroom ? Schedule::with(['subject', 'teacher'])
            ->where('classroom_id', $classroom->id)
            ->where('day', $day)
            ->orderBy('start_time')
            ->get() : collect([]);
            
        return view('siswa.schedule.day', compact('schedules', 'dayName', 'day', 'classroom'));
    }
}
