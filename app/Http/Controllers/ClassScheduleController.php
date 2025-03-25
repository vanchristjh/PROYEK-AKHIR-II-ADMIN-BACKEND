<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\ClassSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ClassScheduleController extends Controller
{
    /**
     * Display a listing of the schedules.
     */
    public function index(Request $request)
    {
        $query = ClassSchedule::with(['class', 'teacher'])
            ->orderBy('day_of_week')
            ->orderBy('start_time');
            
        // Filters
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }
        
        if ($request->has('teacher_id') && $request->teacher_id) {
            $query->where('teacher_id', $request->teacher_id);
        }
        
        if ($request->has('day') && $request->day) {
            $query->where('day_of_week', $request->day);
        }
        
        if ($request->has('subject') && $request->subject) {
            $query->where('subject', 'LIKE', '%' . $request->subject . '%');
        }
        
        $schedules = $query->get();
        
        // Group schedules by day for easier display
        $schedulesByDay = $schedules->groupBy('day_of_week');
        
        // Get classes and teachers for filter dropdowns
        $classes = ClassRoom::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        
        return view('dashboard.schedules.index', compact(
            'schedules', 
            'schedulesByDay',
            'classes',
            'teachers'
        ));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create()
    {
        $classes = ClassRoom::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        
        // Pre-fill teacher if provided in query string
        $selectedTeacherId = request('teacher_id');
        
        return view('dashboard.schedules.create', compact('classes', 'teachers', 'selectedTeacherId'));
    }

    /**
     * Store a newly created schedule in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:class_rooms,id',
            'teacher_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:20',
            'semester' => 'nullable|in:1,2',
            'description' => 'nullable|string',
            'enable_notification' => 'nullable|boolean',
            'notify_before' => 'nullable|integer|min:1|max:60',
            'notify_email' => 'nullable|boolean',
            'notify_push' => 'nullable|boolean',
        ]);

        // Check for schedule conflicts
        $conflictQuery = ClassSchedule::where('day_of_week', $validated['day_of_week'])
            ->where(function($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    $q->where('start_time', '<=', $validated['start_time'])
                      ->where('end_time', '>', $validated['start_time']);
                })->orWhere(function($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>=', $validated['end_time']);
                });
            });
            
        // Check teacher availability
        $teacherConflict = (clone $conflictQuery)
            ->where('teacher_id', $validated['teacher_id'])
            ->first();
            
        if ($teacherConflict) {
            return back()->withInput()->withErrors([
                'teacher_id' => 'Guru ini sudah memiliki jadwal pada waktu tersebut.'
            ]);
        }
        
        // Check room availability (if room is specified)
        if ($validated['room']) {
            $roomConflict = (clone $conflictQuery)
                ->where('room', $validated['room'])
                ->first();
                
            if ($roomConflict) {
                return back()->withInput()->withErrors([
                    'room' => 'Ruangan ini sudah digunakan pada waktu tersebut.'
                ]);
            }
        }
        
        // Check class availability
        $classConflict = (clone $conflictQuery)
            ->where('class_id', $validated['class_id'])
            ->first();
            
        if ($classConflict) {
            return back()->withInput()->withErrors([
                'class_id' => 'Kelas ini sudah memiliki jadwal pada waktu tersebut.'
            ]);
        }
        
        $validated['created_by'] = Auth::id();
        
        // Check if notification columns exist in the table
        $hasNotificationColumns = Schema::hasColumn('class_schedules', 'notification_enabled');
        
        // Only add notification data if the columns exist
        if ($hasNotificationColumns) {
            $validated['notification_enabled'] = $request->has('enable_notification');
            $validated['notify_minutes_before'] = $request->input('notify_before', 15);
            $validated['notify_by_email'] = $request->has('notify_email');
            $validated['notify_by_push'] = $request->has('notify_push');
        }
        
        ClassSchedule::create($validated);

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal pelajaran berhasil ditambahkan!');
    }

    /**
     * Display the specified schedule.
     */
    public function show(ClassSchedule $schedule)
    {
        return view('dashboard.schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified schedule.
     */
    public function edit(ClassSchedule $schedule)
    {
        $classes = ClassRoom::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        
        return view('dashboard.schedules.edit', compact('schedule', 'classes', 'teachers'));
    }

    /**
     * Update the specified schedule in storage.
     */
    public function update(Request $request, ClassSchedule $schedule)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:class_rooms,id',
            'teacher_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:20',
            'semester' => 'nullable|in:1,2',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'enable_notification' => 'nullable|boolean',
            'notify_before' => 'nullable|integer|min:1|max:60',
            'notify_email' => 'nullable|boolean',
            'notify_push' => 'nullable|boolean',
        ]);

        // Check for schedule conflicts (excluding this schedule)
        $conflictQuery = ClassSchedule::where('id', '!=', $schedule->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    $q->where('start_time', '<=', $validated['start_time'])
                      ->where('end_time', '>', $validated['start_time']);
                })->orWhere(function($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>=', $validated['end_time']);
                });
            });
            
        // Check teacher availability
        $teacherConflict = (clone $conflictQuery)
            ->where('teacher_id', $validated['teacher_id'])
            ->first();
            
        if ($teacherConflict) {
            return back()->withInput()->withErrors([
                'teacher_id' => 'Guru ini sudah memiliki jadwal pada waktu tersebut.'
            ]);
        }
        
        // Check room availability (if room is specified)
        if ($validated['room']) {
            $roomConflict = (clone $conflictQuery)
                ->where('room', $validated['room'])
                ->first();
                
            if ($roomConflict) {
                return back()->withInput()->withErrors([
                    'room' => 'Ruangan ini sudah digunakan pada waktu tersebut.'
                ]);
            }
        }
        
        // Check class availability
        $classConflict = (clone $conflictQuery)
            ->where('class_id', $validated['class_id'])
            ->first();
            
        if ($classConflict) {
            return back()->withInput()->withErrors([
                'class_id' => 'Kelas ini sudah memiliki jadwal pada waktu tersebut.'
            ]);
        }
        
        $validated['is_active'] = $request->has('is_active');
        
        // Check if notification columns exist in the table
        $hasNotificationColumns = Schema::hasColumn('class_schedules', 'notification_enabled');
        
        // Only add notification data if the columns exist
        if ($hasNotificationColumns) {
            $validated['notification_enabled'] = $request->has('enable_notification');
            $validated['notify_minutes_before'] = $request->input('notify_before', 15);
            $validated['notify_by_email'] = $request->has('notify_email');
            $validated['notify_by_push'] = $request->has('notify_push');
        }
        
        $schedule->update($validated);

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal pelajaran berhasil diperbarui!');
    }

    /**
     * Remove the specified schedule from storage.
     */
    public function destroy(ClassSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal pelajaran berhasil dihapus!');
    }

    /**
     * Display the weekly schedule view.
     */
    public function weekly(Request $request)
    {
        $classId = $request->input('class_id');
        $teacherId = $request->input('teacher_id');
        
        $query = ClassSchedule::with(['class', 'teacher'])->active();
        
        if ($classId) {
            $query->where('class_id', $classId);
        }
        
        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }
        
        $schedules = $query->get();
        
        // Organize schedules by day and time for the weekly view
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $timeSlots = $this->generateTimeSlots('07:00', '17:00', 60); // 7 AM to 5 PM, 1-hour slots
        
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
        
        $classes = ClassRoom::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        
        return view('dashboard.schedules.weekly', compact(
            'weeklySchedule',
            'days',
            'timeSlots',
            'classes',
            'teachers',
            'classId',
            'teacherId'
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
