<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\ClassSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentScheduleController extends Controller
{
    /**
     * Display the student's class schedule.
     */
    public function index(Request $request)
    {
        // Get the class ID from the request or from the authenticated user's class
        $classId = $request->input('class_id');
        
        if (!$classId && Auth::user()->role === 'student') {
            $student = User::with('studentClass')->find(Auth::id());
            $classId = $student->studentClass->id ?? null;
        }
        
        // If we still don't have a class ID, get the first class
        if (!$classId) {
            $firstClass = ClassRoom::first();
            $classId = $firstClass ? $firstClass->id : null;
        }
        
        // Get the class
        $class = ClassRoom::find($classId);
        
        // Get schedules for this class
        $schedules = ClassSchedule::with(['class', 'teacher'])
            ->where('class_id', $classId)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
        
        // Group schedules by day for easier display
        $schedulesByDay = $schedules->groupBy('day_of_week');
        
        // Get all classes for the dropdown
        $classes = ClassRoom::orderBy('name')->get();
        
        return view('dashboard.schedules.student', compact(
            'schedules', 
            'schedulesByDay',
            'class',
            'classes'
        ));
    }

    /**
     * Display the student's class schedule in weekly view.
     */
    public function weekly(Request $request)
    {
        // Get the class ID from the request or from the authenticated user's class
        $classId = $request->input('class_id');
        
        if (!$classId && Auth::user()->role === 'student') {
            $student = User::with('studentClass')->find(Auth::id());
            $classId = $student->studentClass->id ?? null;
        }
        
        // If we still don't have a class ID, get the first class
        if (!$classId) {
            $firstClass = ClassRoom::first();
            $classId = $firstClass ? $firstClass->id : null;
        }
        
        // Get the class
        $class = ClassRoom::find($classId);
        
        // Get schedules for this class
        $schedules = ClassSchedule::with(['class', 'teacher'])
            ->where('class_id', $classId)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
        
        // Days of the week
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        // Time slots for the weekly view
        $timeSlots = $this->generateTimeSlots('07:00', '17:00', 60); // 7 AM to 5 PM, 1-hour slots
        
        // Organize schedules by day and time for the weekly view
        $weeklySchedule = [];
        foreach ($days as $day) {
            $weeklySchedule[$day] = [];
            foreach ($timeSlots as $timeSlot) {
                $weeklySchedule[$day][$timeSlot] = [];
            }
        }
        
        foreach ($schedules as $schedule) {
            $startHour = $schedule->start_time->format('H:i');
            
            // Find the appropriate time slot
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
        
        // Get all classes for the dropdown
        $classes = ClassRoom::orderBy('name')->get();
        
        return view('dashboard.schedules.student-weekly', compact(
            'weeklySchedule',
            'days',
            'timeSlots',
            'class',
            'classes'
        ));
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
