<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class AdminScheduleController extends Controller
{
    /**
     * Display a listing of schedules
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check if the day column exists, if not, tell the user to run migrations
        if (!Schema::hasColumn('schedules', 'day')) {
            return view('admin.schedule.index', [
                'schedules' => collect(),
                'message' => 'Database schema perlu diperbarui. Silakan jalankan perintah "php artisan migrate" atau hubungi administrator.'
            ]);
        }

        try {
            // Base query
            $query = Schedule::with(['subject', 'classroom', 'teacher']);
            
            // Apply filters
            if ($request->filled('classroom')) {
                $query->where('classroom_id', $request->classroom);
            }
            
            if ($request->filled('teacher')) {
                $query->where('teacher_id', $request->teacher);
            }
            
            if ($request->filled('subject')) {
                $query->where('subject_id', $request->subject);
            }
            
            if ($request->filled('day')) {
                $query->where('day', $request->day);
            }
            
            // Get all schedules with sorting
            $schedules = $query->orderBy('day')
                ->orderBy('start_time')
                ->paginate(15);

            // Get data for filters            
            $classrooms = Classroom::all();
            $subjects = Subject::all();
            $teachers = User::role('guru')->get();
            
            // Define day names for display
            $dayNames = [
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
                7 => 'Minggu'
            ];
            
            return view('admin.schedule.index', compact('schedules', 'classrooms', 'subjects', 'teachers', 'dayNames'));
        } catch (\Exception $e) {
            return view('admin.schedule.index', [
                'message' => 'Terjadi kesalahan saat mengambil data jadwal. Detail: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new schedule
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teachers = User::role('guru')->get();
        $subjects = Subject::all();
        $classrooms = Classroom::all();
        
        return view('admin.schedule.create', compact('teachers', 'subjects', 'classrooms'));
    }

    /**
     * Store a newly created schedule
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|integer|min:1|max:7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }        // Check for schedule conflicts
        $conflictDetails = $this->getScheduleConflictDetails(
            $request->day,
            $request->start_time,
            $request->end_time,
            $request->classroom_id,
            $request->teacher_id
        );

        if ($conflictDetails) {
            return redirect()->back()
                ->with('error', 'Jadwal bentrok: ' . $conflictDetails['message'])
                ->withInput();
        }

        // Create schedule
        $schedule = Schedule::create([
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'classroom_id' => $request->classroom_id,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'room' => $request->room,
        ]);

        return redirect()->route('admin.schedule.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Show details of a specific schedule
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schedule = Schedule::with(['subject', 'classroom', 'teacher'])->findOrFail($id);
        
        $dayNames = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];
        
        return view('admin.schedule.show', compact('schedule', 'dayNames'));
    }

    /**
     * Show the form for editing a schedule
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $teachers = User::role('guru')->get();
        $subjects = Subject::all();
        $classrooms = Classroom::all();
        
        return view('admin.schedule.edit', compact('schedule', 'teachers', 'subjects', 'classrooms'));
    }

    /**
     * Update a schedule
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|integer|min:1|max:7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }        // Check for schedule conflicts (excluding the current schedule)
        $conflictDetails = $this->getScheduleConflictDetails(
            $request->day,
            $request->start_time,
            $request->end_time,
            $request->classroom_id,
            $request->teacher_id,
            $id
        );

        if ($conflictDetails) {
            return redirect()->back()
                ->with('error', 'Jadwal bentrok: ' . $conflictDetails['message'])
                ->withInput();
        }

        // Update schedule
        $schedule->update([
            'subject_id' => $request->subject_id,
            'classroom_id' => $request->classroom_id,
            'teacher_id' => $request->teacher_id,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'room' => $request->room,
        ]);

        return redirect()->route('admin.schedule.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Delete a schedule
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        
        return redirect()->route('admin.schedule.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }    /**
     * Check for schedule conflicts
     *
     * @param int $day
     * @param string $startTime
     * @param string $endTime
     * @param int $classroomId
     * @param int $teacherId
     * @param int|null $excludeId
     * @return bool
     */
    private function checkScheduleConflicts($day, $startTime, $endTime, $classroomId, $teacherId, $excludeId = null)
    {
        $query = Schedule::where('day', $day)
            ->where(function($q) use ($startTime, $endTime) {
                // Check if times overlap
                $q->where(function($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<=', $startTime)
                      ->where('end_time', '>', $startTime);
                })->orWhere(function($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>=', $endTime);
                })->orWhere(function($q) use ($startTime, $endTime) {
                    $q->where('start_time', '>=', $startTime)
                      ->where('end_time', '<=', $endTime);
                });
            })
            ->where(function($q) use ($classroomId, $teacherId) {
                // Check conflicts for classroom or teacher
                $q->where('classroom_id', $classroomId)
                  ->orWhere('teacher_id', $teacherId);
            });
        
        // Exclude current schedule if updating
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }
    
    /**
     * Get conflict details for better error messages
     * 
     * @param int $day
     * @param string $startTime
     * @param string $endTime
     * @param int $classroomId
     * @param int $teacherId
     * @param int|null $excludeId
     * @return array|null
     */
    private function getScheduleConflictDetails($day, $startTime, $endTime, $classroomId, $teacherId, $excludeId = null)
    {
        $dayNames = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];
        
        $query = Schedule::with(['subject', 'classroom', 'teacher'])
            ->where('day', $day)
            ->where(function($q) use ($startTime, $endTime) {
                // Check if times overlap
                $q->where(function($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<=', $startTime)
                      ->where('end_time', '>', $startTime);
                })->orWhere(function($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>=', $endTime);
                })->orWhere(function($q) use ($startTime, $endTime) {
                    $q->where('start_time', '>=', $startTime)
                      ->where('end_time', '<=', $endTime);
                });
            })
            ->where(function($q) use ($classroomId, $teacherId) {
                // Check conflicts for classroom or teacher
                $q->where('classroom_id', $classroomId)
                  ->orWhere('teacher_id', $teacherId);
            });
        
        // Exclude current schedule if updating
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        $conflict = $query->first();
        
        if ($conflict) {
            $conflictType = [];
            $conflictMessages = [];
            
            // Classroom conflict
            if ($conflict->classroom_id == $classroomId) {
                $conflictType[] = 'classroom';
                $conflictMessages[] = "Kelas {$conflict->classroom->name} sudah memiliki jadwal pada hari {$dayNames[$conflict->day]} pukul " . 
                    date('H:i', strtotime($conflict->start_time)) . " - " . date('H:i', strtotime($conflict->end_time)) . 
                    " ({$conflict->subject->name} - {$conflict->teacher->name})";
            }
            
            // Teacher conflict
            if ($conflict->teacher_id == $teacherId) {
                $conflictType[] = 'teacher';
                $conflictMessages[] = "Guru {$conflict->teacher->name} sudah memiliki jadwal pada hari {$dayNames[$conflict->day]} pukul " . 
                    date('H:i', strtotime($conflict->start_time)) . " - " . date('H:i', strtotime($conflict->end_time)) . 
                    " (Kelas {$conflict->classroom->name} - {$conflict->subject->name})";
            }
            
            return [
                'schedule' => $conflict,
                'type' => $conflictType,
                'message' => implode("\n", $conflictMessages)
            ];
        }
        
        return null;
    }

    /**
     * Get schedules by classroom for AJAX requests
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSchedulesByClassroom(Request $request)
    {
        $classroomId = $request->classroom_id;
        
        $schedules = Schedule::with(['subject', 'teacher'])
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
            
        $schedulesByDay = [];
        foreach ($dayNames as $key => $day) {
            $schedulesByDay[$key] = $schedules->filter(function($schedule) use ($key) {
                return $schedule->day == $key;
            })->values();
        }
            
        return response()->json([
            'schedules' => $schedulesByDay,
            'dayNames' => $dayNames
        ]);
    }
    
    /**
     * Display the bulk create form
     * 
     * @return \Illuminate\Http\Response
     */
    public function bulkCreate()
    {
        $teachers = User::role('guru')->get();
        $subjects = Subject::all();
        $classrooms = Classroom::all();
        
        return view('admin.schedule.bulk-create', compact('teachers', 'subjects', 'classrooms'));
    }
      /**
     * Store multiple schedules at once
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'classroom_id' => 'required|exists:classrooms,id',
            'entries' => 'required|array|min:1',
            'entries.*.subject_id' => 'required|exists:subjects,id',
            'entries.*.teacher_id' => 'required|exists:users,id',
            'entries.*.day' => 'required|integer|min:1|max:7',
            'entries.*.start_time' => 'required|date_format:H:i',
            'entries.*.end_time' => 'required|date_format:H:i|after:entries.*.start_time',
            'entries.*.room' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $classroomId = $request->classroom_id;
        $successCount = 0;
        $conflictCount = 0;
        $conflicts = [];

        foreach ($request->entries as $key => $entry) {
            // Check for schedule conflicts
            $conflictDetails = $this->getScheduleConflictDetails(
                $entry['day'],
                $entry['start_time'],
                $entry['end_time'],
                $classroomId,
                $entry['teacher_id']
            );

            if ($conflictDetails) {
                $conflictCount++;
                $conflicts[] = "Jadwal #" . ($key + 1) . ": " . $conflictDetails['message'];
                continue;
            }

            // Create schedule
            Schedule::create([
                'subject_id' => $entry['subject_id'],
                'teacher_id' => $entry['teacher_id'],
                'classroom_id' => $classroomId,
                'day' => $entry['day'],
                'start_time' => $entry['start_time'],
                'end_time' => $entry['end_time'],
                'room' => $entry['room'],
            ]);
            
            $successCount++;
        }

        if ($successCount > 0 && $conflictCount > 0) {
            return redirect()->route('admin.schedule.index')
                ->with('success', "{$successCount} jadwal berhasil ditambahkan. {$conflictCount} jadwal gagal karena konflik.")
                ->with('error', implode("\n", $conflicts));
        } elseif ($successCount > 0) {
            return redirect()->route('admin.schedule.index')
                ->with('success', "{$successCount} jadwal berhasil ditambahkan.");
        } else {
            return redirect()->back()
                ->with('error', "Semua jadwal gagal ditambahkan karena konflik:\n" . implode("\n", $conflicts))
                ->withInput();
        }
    }
    
    /**
     * Display the calendar view of schedules
     * 
     * @return \Illuminate\Http\Response
     */
    public function calendar()
    {
        $teachers = User::role('guru')->get();
        $classrooms = Classroom::all();
        
        // Generate time slots (7 AM to 7 PM)
        $timeSlots = [];
        for ($hour = 7; $hour <= 19; $hour++) {
            $timeSlots[] = sprintf("%02d:00", $hour);
        }
        
        return view('admin.schedule.calendar', compact('teachers', 'classrooms', 'timeSlots'));
    }
      /**
     * Get schedule data for calendar view
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCalendarData(Request $request)
    {
        $query = Schedule::with(['subject', 'classroom', 'teacher']);
        
        if ($request->filled('classroom')) {
            $query->where('classroom_id', $request->classroom);
        }
        
        if ($request->filled('teacher')) {
            $query->where('teacher_id', $request->teacher);
        }
        
        $schedules = $query->orderBy('day')
            ->orderBy('start_time')
            ->get();
            
        return response()->json([
            'schedules' => $schedules
        ]);
    }
      /**
     * Export schedules for a specific classroom
     * 
     * @param int $classroomId
     * @return \Illuminate\Http\Response
     */
    public function exportClassroomSchedule($classroomId)
    {
        $classroom = Classroom::findOrFail($classroomId);
        
        $schedulesByDay = Schedule::getClassroomWeeklySchedule($classroomId);
        
        $dayNames = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];
        
        return view('admin.schedule.export', compact('classroom', 'schedulesByDay', 'dayNames'));
    }
}
