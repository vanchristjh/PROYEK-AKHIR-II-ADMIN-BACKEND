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
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $query = Assignment::with(['classroom', 'subject'])
            ->where('teacher_id', $teacher->id);
        
        // Apply filters if provided
        if ($request->filled('classroom')) {
            $query->where('classroom_id', $request->classroom);
        }
        
        if ($request->filled('subject')) {
            $query->where('subject_id', $request->subject);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $assignments = $query->latest()->paginate(10);
        
        // Add counts for display in view
        foreach ($assignments as $assignment) {
            $assignment->submissions_count = $assignment->submissions()->count();
            $assignment->students_count = $assignment->classroom->students()->count();
        }
        
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
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'classroom_id' => 'required|exists:classrooms,id',
                'subject_id' => 'required|exists:subjects,id',
                'deadline' => 'required|date|after:today',
                'max_score' => 'required|numeric|min:1',
                'attachment' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip', // 10MB max
            ]);
            
            // Add teacher_id to the validated data
            $validated['teacher_id'] = Auth::id();
            
            // Handle attachment upload
            if ($request->hasFile('attachment')) {
                try {
                    $attachmentPath = $request->file('attachment')->store('assignments', 'public');
                    $validated['attachment_path'] = $attachmentPath;
                } catch (\Exception $e) {
                    return redirect()->back()->withInput()->with('error', 'Gagal mengunggah file: ' . $e->getMessage());
                }
            }
            
            Assignment::create($validated);
            
            return redirect()->route('guru.assignments.index')
                ->with('success', 'Tugas berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Gagal membuat tugas: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified assignment
     */
    public function show(Assignment $assignment)
    {
        // Check if the teacher is authorized to view this assignment
        if ($assignment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $submissions = $assignment->submissions()->with('student')->get();
        
        return view('guru.assignments.show', compact('assignment', 'submissions'));
    }

    /**
     * Show form to edit an assignment
     */
    public function edit(Assignment $assignment)
    {
        // Check if the teacher is authorized to edit this assignment
        if ($assignment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
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
        // Check if the teacher is authorized to update this assignment
        if ($assignment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'deadline' => 'required|date',
            'max_score' => 'required|numeric|min:1',
            'attachment' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip', // 10MB max
            'remove_attachment' => 'nullable|boolean',
        ]);
        
        // Remove attachment if requested
        if ($request->has('remove_attachment') && $request->remove_attachment) {
            if ($assignment->attachment_path) {
                Storage::disk('public')->delete($assignment->attachment_path);
                $assignment->attachment_path = null;
            }
        }
        
        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($assignment->attachment_path) {
                Storage::disk('public')->delete($assignment->attachment_path);
            }
            
            try {
                $attachmentPath = $request->file('attachment')->store('assignments', 'public');
                $validated['attachment_path'] = $attachmentPath;
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Gagal mengunggah file: ' . $e->getMessage());
            }
        }
        
        // Remove unnecessary fields
        unset($validated['remove_attachment']);
        
        try {
            $assignment->update($validated);
            
            return redirect()->route('guru.assignments.index')
                ->with('success', 'Tugas berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Gagal memperbarui tugas: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified assignment
     */
    public function destroy(Assignment $assignment)
    {
        // Check if the teacher is authorized to delete this assignment
        if ($assignment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Delete attachment if exists
        if ($assignment->attachment_path) {
            Storage::disk('public')->delete($assignment->attachment_path);
        }
        
        // Delete all related submissions
        $assignment->submissions()->delete();
        
        // Delete the assignment
        $assignment->delete();
        
        return redirect()->route('guru.assignments.index')
            ->with('success', 'Tugas berhasil dihapus!');
    }
}
