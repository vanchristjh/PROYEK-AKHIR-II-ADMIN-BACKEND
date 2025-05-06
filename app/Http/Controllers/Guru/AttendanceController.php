<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the attendances.
     */
    public function index(Request $request)
    {
        $query = Attendance::with(['classroom', 'subject', 'details.student'])
            ->where('recorded_by', Auth::id()); // Changed from teacher_id to recorded_by
        
        // Apply filters if provided
        if ($request->filled('classroom')) {
            $query->where('classroom_id', $request->classroom);
        }
        
        if ($request->filled('subject')) {
            $query->where('subject_id', $request->subject);
        }
        
        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }
        
        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $classrooms = Auth::user()->teachingClassrooms() ?? collect([]);
        $subjects = Auth::user()->teacherSubjects ?? collect([]);
        
        return view('guru.attendance.index', compact('attendances', 'classrooms', 'subjects'));
    }

    /**
     * Show the form for creating a new attendance record.
     */
    public function create()
    {
        $user = Auth::user();
        $classrooms = $user->teachingClassrooms()->distinct()->get();
        $subjects = $user->teacherSubjects;
        
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
            'status.*' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string|max:255',
        ]);
        
        $user = Auth::user();
        $classroomId = $request->classroom_id;
        $subjectId = $request->subject_id;
        $date = $request->date;
        
        // Check if attendance already exists for this date, class and subject
        $existingAttendance = Attendance::where('classroom_id', $classroomId)
                                      ->where('subject_id', $subjectId)
                                      ->where('date', $date)
                                      ->where('recorded_by', $user->id) // Changed from teacher_id to recorded_by
                                      ->exists();
                                      
        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Attendance for this class, subject and date already exists.');
        }
        
        // Get the students from the classroom
        $students = User::whereHas('role', function($q) {
                $q->where('slug', 'siswa');
            })
            ->where('classroom_id', $classroomId)
            ->get();
            
        // Save attendance for each student
        foreach ($students as $student) {
            if (isset($request->status[$student->id])) {
                Attendance::create([
                    'date' => $date,
                    'classroom_id' => $classroomId,
                    'subject_id' => $subjectId,
                    'recorded_by' => $user->id, // Changed from teacher_id to recorded_by
                    'student_id' => $student->id,
                    'status' => $request->status[$student->id],
                    'notes' => $request->notes[$student->id] ?? null,
                ]);
            }
        }
        
        return redirect()->route('guru.attendance.index')
                         ->with('success', 'Attendance recorded successfully.');
    }

    /**
     * Update the specified attendance record.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string|max:255',
        ]);
        
        $attendance = Attendance::findOrFail($id);
        
        // Ensure the teacher owns this attendance record
        if ($attendance->recorded_by !== Auth::id()) { // Changed from teacher_id to recorded_by
            abort(403, 'Unauthorized action.');
        }
        
        $attendance->status = $request->status;
        $attendance->notes = $request->notes;
        $attendance->save();
        
        return redirect()->route('guru.attendance.index')
                         ->with('success', 'Attendance updated successfully.');
    }
}
