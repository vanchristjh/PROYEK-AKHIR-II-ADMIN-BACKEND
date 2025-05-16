<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the schedules.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Schedule::with(['classroom', 'subject', 'teacher']);
        
        // Filter by classroom if provided
        if ($request->has('classroom_id') && !empty($request->classroom_id)) {
            $query->where('classroom_id', $request->classroom_id);
        }
        
        // Filter by teacher if provided
        if ($request->has('teacher_id') && !empty($request->teacher_id)) {
            $query->where('teacher_id', $request->teacher_id);
        }
        
        // Filter by day if provided
        if ($request->has('day') && !empty($request->day)) {
            $query->where('day', $request->day);
        }
        
        $schedules = $query->orderBy('day')->orderBy('start_time')->paginate(20);
        $classrooms = Classroom::orderBy('name')->get();
        $teachers = User::where('role_id', 2)->orderBy('name')->get(); // Assuming role_id 2 is for teachers
        
        return view('admin.schedule.index', compact('schedules', 'classrooms', 'teachers'));
    }

    /**
     * Show the form for creating a new schedule.
     *
     * @return \Illuminate\Http\Response
     */    public function create()
    {
        $classrooms = Classroom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        
        // More flexible teacher query - look for definite teachers first, then fall back to any potential teachers
        $teachers = User::where('role_id', 2)->orderBy('name')->get();
        
        // If no teachers found with role_id=2, look for any users that might be teachers
        // based on other attributes (This is a fallback measure)
        if ($teachers->isEmpty()) {
            $teachers = User::where(function($query) {
                    $query->whereNotNull('nip')
                        ->orWhere('email', 'like', '%guru%')
                        ->orWhere('email', 'like', '%teacher%')
                        ->orWhere('name', 'like', '%guru%')
                        ->orWhere('name', 'like', '%teacher%');
                })
                ->orderBy('name')
                ->get();
        }
        
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        return view('admin.schedule.create', compact('classrooms', 'subjects', 'teachers', 'days'));
    }

    /**
     * Store a newly created schedule in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|string|max:10',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for schedule conflicts
        $conflicts = $this->checkScheduleConflicts(
            $request->classroom_id,
            $request->teacher_id,
            $request->day,
            $request->start_time,
            $request->end_time
        );

        if ($conflicts['hasConflict']) {
            return redirect()->back()
                ->with('error', $conflicts['message'])
                ->withInput();
        }

        Schedule::create($request->all());

        return redirect()->route('admin.schedule.index')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    /**
     * Display the specified schedule.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        $schedule->load(['classroom', 'subject', 'teacher']);
        return view('admin.schedule.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified schedule.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {
        $classrooms = Classroom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role_id', 2)->orderBy('name')->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        return view('admin.schedule.edit', compact('schedule', 'classrooms', 'subjects', 'teachers', 'days'));
    }

    /**
     * Update the specified schedule in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validator = Validator::make($request->all(), [
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|string|max:10',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for schedule conflicts (excluding the current schedule)
        $conflicts = $this->checkScheduleConflicts(
            $request->classroom_id,
            $request->teacher_id,
            $request->day,
            $request->start_time,
            $request->end_time,
            $schedule->id
        );

        if ($conflicts['hasConflict']) {
            return redirect()->back()
                ->with('error', $conflicts['message'])
                ->withInput();
        }

        $schedule->update($request->all());

        return redirect()->route('admin.schedule.index')
            ->with('success', 'Jadwal berhasil diperbarui');
    }

    /**
     * Remove the specified schedule from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedule.index')
            ->with('success', 'Jadwal berhasil dihapus');
    }

    /**
     * Display the calendar view of schedules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function calendar(Request $request)
    {
        $classrooms = Classroom::orderBy('name')->get();
        $selectedClassroom = null;
        $schedules = [];
        
        if ($request->has('classroom_id') && !empty($request->classroom_id)) {
            $selectedClassroom = Classroom::findOrFail($request->classroom_id);
            $schedules = Schedule::with(['subject', 'teacher'])
                ->where('classroom_id', $request->classroom_id)
                ->orderBy('day')
                ->orderBy('start_time')
                ->get()
                ->groupBy('day');
        }
        
        return view('admin.schedule.calendar', compact('classrooms', 'selectedClassroom', 'schedules'));
    }

    /**
     * Retrieve schedules for the authenticated teacher.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTeacherSchedules()
    {
        try {
            $userId = auth()->id(); // Get the authenticated user's ID
            
            // Fix the query to properly filter by teacher ID
            $schedules = Schedule::with('teacher')
                ->whereHas('teacher', function ($query) use ($userId) {
                    $query->where('id', $userId); // Changed from 'user_id' to 'id'
                })
                ->orderBy('day', 'asc')
                ->orderBy('start_time', 'asc')
                ->get();
                
            return view('teacher.schedule.index', compact('schedules'));
        } catch (\Exception $e) {
            // Handle error properly
            return back()->with('error', 'Terjadi kesalahan saat mengambil data jadwal. Silakan hubungi administrator: ' . $e->getMessage());
        }
    }

    /**
     * Check for schedule conflicts.
     *
     * @param  int  $classroomId
     * @param  int  $teacherId
     * @param  string  $day
     * @param  string  $startTime
     * @param  string  $endTime
     * @param  int|null  $excludeScheduleId
     * @return array
     */
    private function checkScheduleConflicts($classroomId, $teacherId, $day, $startTime, $endTime, $excludeScheduleId = null)
    {
        // Check classroom conflicts (same classroom at the same time)
        $classroomQuery = Schedule::where('classroom_id', $classroomId)
            ->where('day', $day)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            });

        if ($excludeScheduleId) {
            $classroomQuery->where('id', '!=', $excludeScheduleId);
        }

        $classroomConflict = $classroomQuery->first();
        
        if ($classroomConflict) {
            $classroom = Classroom::find($classroomId);
            return [
                'hasConflict' => true,
                'message' => "Jadwal bentrok dengan kelas {$classroom->name} pada {$day}, {$classroomConflict->start_time}-{$classroomConflict->end_time}"
            ];
        }

        // Check teacher conflicts (same teacher at the same time)
        $teacherQuery = Schedule::where('teacher_id', $teacherId)
            ->where('day', $day)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            });

        if ($excludeScheduleId) {
            $teacherQuery->where('id', '!=', $excludeScheduleId);
        }

        $teacherConflict = $teacherQuery->first();
        
        if ($teacherConflict) {
            $teacher = User::find($teacherId);
            return [
                'hasConflict' => true,
                'message' => "Jadwal bentrok dengan guru {$teacher->name} pada {$day}, {$teacherConflict->start_time}-{$teacherConflict->end_time}"
            ];
        }

        return ['hasConflict' => false, 'message' => ''];
    }
}
