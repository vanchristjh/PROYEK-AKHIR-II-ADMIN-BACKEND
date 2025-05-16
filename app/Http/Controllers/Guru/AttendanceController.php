<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\User;
use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the attendances.
     */
    public function index(Request $request)
    {
        $teacher = Auth::user();
        
        $query = Attendance::with(['classroom', 'subject'])
            ->where('recorded_by', $teacher->id);
        
        // Apply filters if provided
        if ($request->filled('classroom')) {
            $query->where('classroom_id', $request->classroom);
        }
        
        if ($request->filled('subject')) {
            $query->where('subject_id', $request->subject);
        }
        
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        
        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Get classrooms where the teacher teaches
        $classrooms = Classroom::whereHas('subjects.teachers', function($query) use ($teacher) {
            $query->where('subject_teacher.teacher_id', $teacher->id);
        })->get();
        
        // Get subjects taught by the teacher
        $subjects = $teacher->teacherSubjects;
        
        return view('guru.attendance.index', compact('attendances', 'classrooms', 'subjects'));
    }

    /**
     * Show the form for creating a new attendance record.
     */
    public function create()
    {
        $teacher = Auth::user();
        
        // Get classrooms where the teacher teaches
        $classrooms = Classroom::whereHas('subjects.teachers', function ($query) {
            $query->where('users.id', auth()->id());
        })->get();
        
        // Get subjects taught by the teacher
        $subjects = $teacher->teacherSubjects;
        
        return view('guru.attendance.create', compact('classrooms', 'subjects'));
    }

    /**
     * Store a newly created attendance records.
     */
    public function store(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'status' => 'required|array',
            'status.*' => 'required|in:present,absent,late,sick,permitted',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string|max:255',
        ]);
        
        $teacher = Auth::user();
        $classroomId = $request->classroom_id;
        $subjectId = $request->subject_id;
        $date = $request->date;
        
        // Check if attendance already exists for this date, class and subject
        $existingAttendance = Attendance::where('classroom_id', $classroomId)
                                      ->where('subject_id', $subjectId)
                                      ->whereDate('date', $date)
                                      ->where('recorded_by', $teacher->id)
                                      ->exists();
                                      
        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Attendance for this class, subject and date already exists.');
        }
        
        DB::beginTransaction();
        
        try {
            // Create the main attendance record
            $attendance = Attendance::create([
                'date' => $date,
                'classroom_id' => $classroomId,
                'subject_id' => $subjectId,
                'recorded_by' => $teacher->id,
            ]);
            
            // Get the students from the classroom
            $students = User::whereHas('role', function($q) {
                    $q->where('slug', 'siswa');
                })
                ->where('classroom_id', $classroomId)
                ->get();
                
            // Save attendance for each student
            foreach ($students as $student) {
                if (isset($request->status[$student->id])) {
                    AttendanceRecord::create([
                        'attendance_id' => $attendance->id,
                        'student_id' => $student->id,
                        'status' => $request->status[$student->id],
                        'notes' => $request->notes[$student->id] ?? null,
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('guru.attendance.index')
                            ->with('success', 'Attendance recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to record attendance: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified attendance record.
     */
    public function show($id)
    {
        $attendance = Attendance::with(['classroom', 'subject', 'records.student'])
            ->findOrFail($id);
            
        // Ensure the teacher owns this attendance record
        if ($attendance->recorded_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('guru.attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified attendance record.
     */
    public function edit($id)
    {
        $attendance = Attendance::with(['classroom', 'subject', 'records.student'])
            ->findOrFail($id);
            
        // Ensure the teacher owns this attendance record
        if ($attendance->recorded_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('guru.attendance.edit', compact('attendance'));
    }

    /**
     * Update the specified attendance record.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|array',
            'status.*' => 'required|in:present,absent,late,sick,permitted',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string|max:255',
        ]);
        
        $attendance = Attendance::findOrFail($id);
        
        // Ensure the teacher owns this attendance record
        if ($attendance->recorded_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        DB::beginTransaction();
        
        try {
            // Update each student's attendance record
            foreach ($request->status as $studentId => $status) {
                AttendanceRecord::updateOrCreate(
                    [
                        'attendance_id' => $attendance->id,
                        'student_id' => $studentId
                    ],
                    [
                        'status' => $status,
                        'notes' => $request->notes[$studentId] ?? null
                    ]
                );
            }
            
            DB::commit();
            
            return redirect()->route('guru.attendance.index')
                            ->with('success', 'Attendance updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update attendance: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified attendance record.
     */
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        
        // Ensure the teacher owns this attendance record
        if ($attendance->recorded_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        DB::beginTransaction();
        
        try {
            // Delete all associated attendance records first
            $attendance->records()->delete();
            
            // Then delete the main attendance record
            $attendance->delete();
            
            DB::commit();
            
            return redirect()->route('guru.attendance.index')
                            ->with('success', 'Attendance deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete attendance: ' . $e->getMessage());
        }
    }
}
