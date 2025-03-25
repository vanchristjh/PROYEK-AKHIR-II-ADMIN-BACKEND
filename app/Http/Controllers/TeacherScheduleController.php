<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherScheduleController extends Controller
{
    /**
     * Display the teacher's schedule.
     */
    public function index(Request $request)
    {
        // Get the teacher ID from the authenticated user or from the request
        $teacherId = $request->input('teacher_id', Auth::id());
        
        // Get the teacher
        $teacher = User::where('role', 'teacher')->findOrFail($teacherId);
        
        // Get the teacher's schedules
        $schedules = ClassSchedule::with(['class', 'teacher'])
            ->where('teacher_id', $teacherId)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
        
        // Group schedules by day for easier display
        $schedulesByDay = $schedules->groupBy('day_of_week');
        
        // Get all teachers for the dropdown
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        
        return view('dashboard.schedules.teacher', compact(
            'schedules', 
            'schedulesByDay',
            'teacher',
            'teachers'
        ));
    }

    /**
     * Display the teacher's schedule in weekly view.
     */
    public function weekly(Request $request)
    {
        // Get the teacher ID from the authenticated user or from the request
        $teacherId = $request->input('teacher_id', Auth::id());
        
        // Get the teacher
        $teacher = User::where('role', 'teacher')->findOrFail($teacherId);
        
        // Get the teacher's schedules
        $schedules = ClassSchedule::with(['class', 'teacher'])
            ->where('teacher_id', $teacherId)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
        
        // Days of the week
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        // Time slots
        $timeSlots = $this->generateTimeSlots('07:00', '17:00', 60);
        
        // Initialize weekly schedule array
        $weeklySchedule = [];
        foreach ($days as $day) {
            $weeklySchedule[$day] = [];
            foreach ($timeSlots as $timeSlot) {
                $weeklySchedule[$day][$timeSlot] = [];
            }
        }
        
        // Populate schedule
        foreach ($schedules as $schedule) {
            $startHour = $schedule->start_time->format('H:i');
            foreach ($timeSlots as $timeSlot) {
                $slotStart = \Carbon\Carbon::createFromFormat('H:i', $timeSlot);
                $slotEnd = (clone $slotStart)->addHour();
                $scheduleStart = \Carbon\Carbon::createFromFormat('H:i', $startHour);
                
                if ($scheduleStart >= $slotStart && $scheduleStart < $slotEnd) {
                    $weeklySchedule[$schedule->day_of_week][$timeSlot][] = $schedule;
                    break;
                }
            }
        }
        
        // Get all teachers for dropdown
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        
        return view('dashboard.schedules.teacher-weekly', compact(
            'weeklySchedule',
            'days',
            'timeSlots',
            'teacher',
            'teachers'
        ));
    }

    /**
     * Show the form for creating a new teacher schedule.
     */
    public function create(Request $request)
    {
        // Get all classes
        $classes = ClassRoom::orderBy('name')->get();
        
        // Get all teachers
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        
        // Pre-fill teacher if provided in query string
        $selectedTeacherId = $request->input('teacher_id');
        
        return view('dashboard.schedules.teacher-form', compact('classes', 'teachers', 'selectedTeacherId'));
    }

    /**
     * Generate time slots for the weekly view.
     */
    private function generateTimeSlots($start, $end, $interval = 60)
    {
        $startTime = \Carbon\Carbon::createFromFormat('H:i', $start);
        $endTime = \Carbon\Carbon::createFromFormat('H:i', $end);
        
        $slots = [];
        $current = clone $startTime;
        
        while ($current < $endTime) {
            $slots[] = $current->format('H:i');
            $current->addMinutes($interval);
        }
        
        return $slots;
    }
}
