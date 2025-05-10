<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teacher = Auth::user();
        
        // Check if the day column exists, if not, tell the user to run migrations
        if (!Schema::hasColumn('schedules', 'day')) {
            return view('guru.schedule.index', [
                'schedules' => collect(),
                'schedulesByDay' => [],
                'message' => 'Database schema perlu diperbarui. Silakan jalankan perintah "php artisan migrate" atau hubungi administrator.'
            ]);
        }
        
        try {
            // Get all schedules for this teacher
            $schedules = Schedule::with(['subject', 'classroom'])
                ->forTeacher($teacher->id)
                ->orderBy('day')
                ->orderBy('start_time')
                ->get();
            
            // Organize schedules by day for easier display
            $schedulesByDay = [
                'Senin' => [],
                'Selasa' => [],
                'Rabu' => [],
                'Kamis' => [],
                'Jumat' => [],
                'Sabtu' => [],
                'Minggu' => []
            ];
            
            foreach ($schedules as $schedule) {
                $schedulesByDay[$schedule->dayName][] = $schedule;
            }
            
            return view('guru.schedule.index', [
                'schedules' => $schedules,
                'schedulesByDay' => $schedulesByDay
            ]);
        } catch (\Exception $e) {
            return view('guru.schedule.index', [
                'schedules' => collect(),
                'schedulesByDay' => [],
                'message' => 'Terjadi kesalahan saat mengambil data jadwal. Silakan hubungi administrator: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new schedule.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teacher = Auth::user();
        $subjects = $teacher->teacherSubjects;
        $classrooms = Classroom::all();
        
        return view('guru.schedule.create', [
            'subjects' => $subjects,
            'classrooms' => $classrooms
        ]);
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
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'day' => 'required|integer|min:1|max:7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for schedule conflicts
        $conflicts = $this->checkScheduleConflicts(
            $request->day,
            $request->start_time,
            $request->end_time,
            $request->classroom_id,
            Auth::id()
        );

        if ($conflicts) {
            return redirect()->back()
                ->with('error', 'Jadwal bentrok dengan jadwal yang sudah ada.')
                ->withInput();
        }

        // Create schedule
        $schedule = Schedule::create([
            'subject_id' => $request->subject_id,
            'teacher_id' => Auth::id(),
            'classroom_id' => $request->classroom_id,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'room' => $request->room,
        ]);

        return redirect()->route('guru.schedule.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified schedule.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $teacher = Auth::user();
        $schedule = Schedule::findOrFail($id);
        
        // Check if teacher owns this schedule
        if ($schedule->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $subjects = $teacher->teacherSubjects;
        $classrooms = Classroom::all();
        
        return view('guru.schedule.edit', [
            'schedule' => $schedule,
            'subjects' => $subjects,
            'classrooms' => $classrooms
        ]);
    }

    /**
     * Update the specified schedule in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        
        // Check if teacher owns this schedule
        if ($schedule->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'day' => 'required|integer|min:1|max:7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for schedule conflicts (excluding the current schedule)
        $conflicts = $this->checkScheduleConflicts(
            $request->day,
            $request->start_time,
            $request->end_time,
            $request->classroom_id,
            Auth::id(),
            $id
        );

        if ($conflicts) {
            return redirect()->back()
                ->with('error', 'Jadwal bentrok dengan jadwal yang sudah ada.')
                ->withInput();
        }

        // Update schedule
        $schedule->update([
            'subject_id' => $request->subject_id,
            'classroom_id' => $request->classroom_id,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'room' => $request->room,
        ]);

        return redirect()->route('guru.schedule.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Remove the specified schedule from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        
        // Check if teacher owns this schedule
        if ($schedule->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $schedule->delete();
        
        return redirect()->route('guru.schedule.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Check for schedule conflicts
     *
     * @param int $day
     * @param string $startTime
     * @param string $endTime
     * @param int $classroomId
     * @param int $teacherId
     * @param int|null $excludeId
     * @return bool
     */    private function checkScheduleConflicts($day, $startTime, $endTime, $classroomId, $teacherId, $excludeId = null)
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
            return [
                'schedule' => $conflict,
                'type' => $conflict->classroom_id == $classroomId ? 'classroom' : 'teacher',
                'message' => $conflict->classroom_id == $classroomId 
                    ? "Kelas {$conflict->classroom->name} sudah memiliki jadwal pada hari dan waktu tersebut" 
                    : "Guru {$conflict->teacher->name} sudah memiliki jadwal pada hari dan waktu tersebut"
            ];
        }
        
        return null;
    }
}