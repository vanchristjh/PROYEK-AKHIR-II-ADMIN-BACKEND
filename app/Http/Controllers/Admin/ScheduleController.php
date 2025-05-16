<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $classroomFilter = $request->input('classroom');
        $dayFilter = $request->input('day');

        $query = Schedule::query()
            ->orderBy('day', 'asc')
            ->orderBy('start_time', 'asc');

        if ($classroomFilter) {
            $query->where('classroom_id', $classroomFilter);
        }

        if ($dayFilter) {
            $query->where('day', $dayFilter);
        }

        $schedules = $query->paginate(15);
        $classrooms = Classroom::orderBy('name')->get();
        $days = $this->getDays();

        return view('admin.schedule.index', compact('schedules', 'classrooms', 'days', 'classroomFilter', 'dayFilter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classrooms = Classroom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        
        // Get teachers from both Teacher model and User model with teacher role
        $teachersFromModel = Teacher::all();
        
        // If we have no teachers in the Teacher model, get them from the User model
        $teachers = $teachersFromModel;
        if ($teachers->isEmpty()) {
            // Find the teacher role ID
            $teacherRole = Role::where('name', 'teacher')->orWhere('name', 'like', '%guru%')->first();
            
            if ($teacherRole) {
                $teacherUsers = User::where('role_id', $teacherRole->id)->get();
                
                if ($teacherUsers->isNotEmpty()) {
                    // Convert User objects to teacher-like objects
                    $teachers = $teacherUsers;
                }
            }
        }
        
        $days = $this->getDays();

        return view('admin.schedule.create', compact('classrooms', 'subjects', 'teachers', 'days'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required',
            'day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room' => 'nullable|string|max:255',
            'school_year' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Verify teacher exists either in Teacher model or User model
        $teacherExists = Teacher::where('id', $validated['teacher_id'])->exists() || 
                         User::where('id', $validated['teacher_id'])->exists();
        
        if (!$teacherExists) {
            return back()->withInput()->with('error', 'ID Guru tidak valid.');
        }

        $validated['created_by'] = Auth::id();
        
        if (empty($validated['school_year'])) {
            $validated['school_year'] = '2023/2024'; // Default value
        }

        try {
            Schedule::create($validated);
            return redirect()->route('admin.schedule.index')->with('success', 'Jadwal berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        $classrooms = Classroom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        
        // Get teachers from both Teacher model and User model with teacher role
        $teachersFromModel = Teacher::all();
        
        // If we have no teachers in the Teacher model, get them from the User model
        $teachers = $teachersFromModel;
        if ($teachers->isEmpty()) {
            // Find the teacher role ID
            $teacherRole = Role::where('name', 'teacher')->orWhere('name', 'like', '%guru%')->first();
            
            if ($teacherRole) {
                $teacherUsers = User::where('role_id', $teacherRole->id)->get();
                
                if ($teacherUsers->isNotEmpty()) {
                    // Use the Users as teachers
                    $teachers = $teacherUsers;
                }
            }
        }
        
        // Make sure we load the current teacher even if it's from User model
        if (!$schedule->teacher && $schedule->teacher_id) {
            $user = User::find($schedule->teacher_id);
            if ($user) {
                // Add this user to the teachers collection if not already there
                $userExists = $teachers->contains(function ($teacher) use ($user) {
                    return $teacher->id == $user->id;
                });
                
                if (!$userExists) {
                    $teachers->push($user);
                }
            }
        }
        
        $days = $this->getDays();

        return view('admin.schedule.edit', compact('schedule', 'classrooms', 'subjects', 'teachers', 'days'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            $schedule->update($validated);
            return redirect()->route('admin.schedule.index')->with('success', 'Jadwal berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        try {
            $schedule->delete();
            return redirect()->route('admin.schedule.index')->with('success', 'Jadwal berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Check for schedule conflicts
     */
    public function checkConflicts(Request $request)
    {
        $classroomId = $request->classroom_id;
        $teacherId = $request->teacher_id;
        $day = $request->day;
        $startTime = $request->start_time;
        $endTime = $request->end_time;
        $scheduleId = $request->schedule_id; // For edit case, to exclude current schedule
        
        $conflicts = [];
        
        // Check classroom schedule conflicts (same classroom, same day, overlapping time)
        $classroomConflicts = Schedule::where('classroom_id', $classroomId)
            ->where('day', $day)
            ->when($scheduleId, function($query, $scheduleId) {
                return $query->where('id', '!=', $scheduleId);
            })
            ->where(function($query) use ($startTime, $endTime) {
                // Time overlaps
                $query->where(function($q) use ($startTime, $endTime) {
                    // New schedule starts during existing schedule
                    $q->where('start_time', '<=', $startTime)
                      ->where('end_time', '>', $startTime);
                })->orWhere(function($q) use ($startTime, $endTime) {
                    // New schedule ends during existing schedule
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>=', $endTime);
                })->orWhere(function($q) use ($startTime, $endTime) {
                    // New schedule contains existing schedule
                    $q->where('start_time', '>=', $startTime)
                      ->where('end_time', '<=', $endTime);
                });
            })
            ->get();
        
        foreach ($classroomConflicts as $conflict) {
            $conflicts[] = [
                'type' => 'classroom',
                'message' => "Konflik dengan kelas {$conflict->classroom->name}: Jadwal sudah ada pada hari {$conflict->day} pukul " . 
                             substr($conflict->start_time, 0, 5) . " - " . substr($conflict->end_time, 0, 5)
            ];
        }
        
        // Check teacher schedule conflicts (same teacher, same day, overlapping time)
        $teacherConflicts = Schedule::where('teacher_id', $teacherId)
            ->where('day', $day)
            ->when($scheduleId, function($query, $scheduleId) {
                return $query->where('id', '!=', $scheduleId);
            })
            ->where(function($query) use ($startTime, $endTime) {
                // Time overlaps (same logic as above)
                $query->where(function($q) use ($startTime, $endTime) {
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
            ->get();
        
        foreach ($teacherConflicts as $conflict) {
            $teacher = $conflict->teacher ? $conflict->teacher->name : 'Unknown Teacher';
            $conflicts[] = [
                'type' => 'teacher',
                'message' => "Konflik dengan guru {$teacher}: Sudah mengajar di kelas {$conflict->classroom->name} pada hari {$conflict->day} pukul " . 
                             substr($conflict->start_time, 0, 5) . " - " . substr($conflict->end_time, 0, 5)
            ];
        }
        
        return response()->json([
            'conflicts' => $conflicts
        ]);
    }

    /**
     * Helper method to check if two time ranges overlap
     */
    private function timesOverlap($start1, $end1, $start2, $end2)
    {
        return ($start1 < $end2 && $end1 > $start2);
    }
    
    /**
     * Get days of the week.
     */
    private function getDays()
    {
        return ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    }
}
