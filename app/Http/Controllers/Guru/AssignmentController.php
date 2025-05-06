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
     * Display a listing of assignments
     */
    public function index()
    {
        $teacher = Auth::user();
        $assignments = Assignment::with(['classroom', 'subject'])
            ->whereHas('subject.teachers', function($query) use ($teacher) {
                $query->where('user_id', $teacher->id);
            })
            ->latest()
            ->paginate(10);
        
        return view('guru.assignments.index', compact('assignments'));
    }

    /**
     * Show form to create a new assignment
     */
    public function create()
    {
        $teacher = Auth::user();
        $subjects = $teacher->teacherSubjects;
        $classrooms = Classroom::all();
        
        return view('guru.assignments.create', compact('subjects', 'classrooms'));
    }

    /**
     * Store a newly created assignment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'due_date' => 'required|date|after:today',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);
        
        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('assignments', 'public');
            $validated['attachment_path'] = $attachmentPath;
        }
        
        Assignment::create($validated);
        
        return redirect()->route('guru.assignments.index')
            ->with('success', 'Tugas berhasil dibuat!');
    }

    /**
     * Display the specified assignment
     */
    public function show(Assignment $assignment)
    {
        $submissions = $assignment->submissions()->with('student')->get();
        
        return view('guru.assignments.show', compact('assignment', 'submissions'));
    }

    /**
     * Show form to edit an assignment
     */
    public function edit(Assignment $assignment)
    {
        $teacher = Auth::user();
        $subjects = $teacher->teacherSubjects;
        $classrooms = Classroom::all();
        
        return view('guru.assignments.edit', compact('assignment', 'subjects', 'classrooms'));
    }

    /**
     * Update the specified assignment
     */
    public function update(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'due_date' => 'required|date',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);
        
        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($assignment->attachment_path) {
                Storage::disk('public')->delete($assignment->attachment_path);
            }
            
            $attachmentPath = $request->file('attachment')->store('assignments', 'public');
            $validated['attachment_path'] = $attachmentPath;
        }
        
        $assignment->update($validated);
        
        return redirect()->route('guru.assignments.index')
            ->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Remove the specified assignment
     */
    public function destroy(Assignment $assignment)
    {
        // Delete attachment if exists
        if ($assignment->attachment_path) {
            Storage::disk('public')->delete($assignment->attachment_path);
        }
        
        $assignment->delete();
        
        return redirect()->route('guru.assignments.index')
            ->with('success', 'Tugas berhasil dihapus!');
    }
}
