<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the assignments.
     */
    public function index(Request $request)
    {
        // Get the student's classroom
        $student = Auth::user();
        $classroom = $student->classroom;
        
        if (!$classroom) {
            return view('siswa.assignments.index', ['assignments' => collect()]);
        }
        
        $query = Assignment::query()
            ->where('classroom_id', $classroom->id)
            ->with(['subject', 'teacher', 'submissions' => function($query) {
                $query->where('student_id', Auth::id());
            }]);
            
        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('subject')) {
            $query->where('subject_id', $request->subject);
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'submitted') {
                $query->whereHas('submissions', function($q) {
                    $q->where('student_id', Auth::id());
                });
            } elseif ($request->status === 'not_submitted') {
                $query->whereDoesntHave('submissions', function($q) {
                    $q->where('student_id', Auth::id());
                });
            }
        }
        
        // Get assignments
        $assignments = $query->latest()->paginate(10);
        
        // Get subjects for filter
        $subjects = $classroom->subjects;
        
        return view('siswa.assignments.index', compact('assignments', 'subjects'));
    }

    /**
     * Display the specified assignment.
     */
    public function show(Assignment $assignment)
    {
        // Check if the assignment belongs to student's classroom
        $student = Auth::user();
        if ($assignment->classroom_id !== $student->classroom_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();
        
        return view('siswa.assignments.show', compact('assignment', 'submission'));
    }

    /**
     * Submit an assignment.
     */
    public function submit(Request $request, Assignment $assignment)
    {
        // Check if the assignment belongs to student's classroom
        $student = Auth::user();
        if ($assignment->classroom_id !== $student->classroom_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if deadline has passed
        if ($assignment->deadline && now() > $assignment->deadline) {
            return redirect()->back()->with('error', 'Batas waktu pengumpulan sudah berakhir!');
        }
        
        // Validate request
        $validated = $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
            'notes' => 'nullable|string'
        ]);
        
        // Check if student has already submitted
        $existingSubmission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();
        
        // Handle file upload
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('submissions', $fileName, 'public');
        
        if ($existingSubmission) {
            // Delete old file if exists
            if ($existingSubmission->file_path) {
                Storage::disk('public')->delete($existingSubmission->file_path);
            }
            
            // Update existing submission
            $existingSubmission->file_path = $filePath;
            $existingSubmission->file_name = $file->getClientOriginalName();
            $existingSubmission->notes = $validated['notes'] ?? null;
            $existingSubmission->save();
            
            return redirect()->route('siswa.assignments.show', $assignment)->with('success', 'Tugas berhasil diperbarui!');
        } else {
            // Create new submission
            AssignmentSubmission::create([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'notes' => $validated['notes'] ?? null
            ]);
            
            return redirect()->route('siswa.assignments.show', $assignment)->with('success', 'Tugas berhasil dikumpulkan!');
        }
    }
}
