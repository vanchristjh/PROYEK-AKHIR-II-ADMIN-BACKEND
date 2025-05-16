<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Assignment::query()
            ->where('teacher_id', Auth::id())
            ->with(['classroom', 'subject']);
            
        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('classroom')) {
            $query->where('classroom_id', $request->classroom);
        }
        
        if ($request->filled('subject')) {
            $query->where('subject_id', $request->subject);
        }
        
        // Add counts for submissions and students
        $assignments = $query->withCount(['submissions', 'classroom as students_count' => function ($query) {
            $query->select(\DB::raw('count(students.id)'))
                  ->join('students', 'students.classroom_id', '=', 'classrooms.id');
        }])
        ->latest()
        ->paginate(10);
        
        return view('guru.assignments.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classrooms = Auth::user()->teachingClassrooms;
        $subjects = Auth::user()->subjects;
        
        return view('guru.assignments.create', compact('classrooms', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'deadline' => 'nullable|date',
            'file' => 'nullable|file|max:10240', // Max 10MB
            'max_score' => 'nullable|numeric|min:0'
        ]);

        $assignment = new Assignment();
        $assignment->title = $validated['title'];
        $assignment->description = $validated['description'];
        $assignment->classroom_id = $validated['classroom_id'];
        $assignment->subject_id = $validated['subject_id'];
        $assignment->teacher_id = Auth::id();
        $assignment->deadline = $validated['deadline'] ?? null;
        $assignment->max_score = $validated['max_score'] ?? 100;
        
        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('assignments', $fileName, 'public');
            $assignment->file_path = $filePath;
            $assignment->file_name = $file->getClientOriginalName();
        }
        
        $assignment->save();
        
        return redirect()->route('guru.assignments.index')
            ->with('success', 'Tugas berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment)
    {
        // Check if assignment belongs to authenticated teacher
        if ($assignment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $assignment->load(['classroom', 'subject', 'submissions.student']);
        
        // Get all students from the classroom
        $students = $assignment->classroom->students;
        
        // Map submission status for each student
        $studentSubmissions = $students->map(function ($student) use ($assignment) {
            $submission = $assignment->submissions->where('student_id', $student->id)->first();
            return [
                'student' => $student,
                'submission' => $submission,
                'status' => $submission ? 'submitted' : 'not_submitted',
                'submitted_at' => $submission ? $submission->created_at : null
            ];
        });
        
        return view('guru.assignments.show', compact('assignment', 'studentSubmissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment)
    {
        // Check if assignment belongs to authenticated teacher
        if ($assignment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $classrooms = Auth::user()->teachingClassrooms;
        $subjects = Auth::user()->subjects;
        
        return view('guru.assignments.edit', compact('assignment', 'classrooms', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assignment $assignment)
    {
        // Check if assignment belongs to authenticated teacher
        if ($assignment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'deadline' => 'nullable|date',
            'file' => 'nullable|file|max:10240', // Max 10MB
            'max_score' => 'nullable|numeric|min:0',
            'remove_file' => 'nullable|boolean'
        ]);
        
        $assignment->title = $validated['title'];
        $assignment->description = $validated['description'];
        $assignment->classroom_id = $validated['classroom_id'];
        $assignment->subject_id = $validated['subject_id'];
        $assignment->deadline = $validated['deadline'] ?? null;
        $assignment->max_score = $validated['max_score'] ?? 100;
        
        // Remove existing file if requested
        if (isset($validated['remove_file']) && $validated['remove_file'] && $assignment->file_path) {
            Storage::disk('public')->delete($assignment->file_path);
            $assignment->file_path = null;
            $assignment->file_name = null;
        }
        
        // Handle new file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($assignment->file_path) {
                Storage::disk('public')->delete($assignment->file_path);
            }
            
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('assignments', $fileName, 'public');
            $assignment->file_path = $filePath;
            $assignment->file_name = $file->getClientOriginalName();
        }
        
        $assignment->save();
        
        return redirect()->route('guru.assignments.index')
            ->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        // Check if assignment belongs to authenticated teacher
        if ($assignment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Delete the file if exists
        if ($assignment->file_path) {
            Storage::disk('public')->delete($assignment->file_path);
        }
        
        // Delete assignment will cascade delete submissions if foreign key is set up correctly
        $assignment->delete();
        
        return redirect()->route('guru.assignments.index')
            ->with('success', 'Tugas berhasil dihapus!');
    }
}
