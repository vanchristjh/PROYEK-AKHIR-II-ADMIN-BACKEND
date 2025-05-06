<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SiswaAssignmentController extends Controller
{
    /**
     * Display all assignments for the student
     */
    public function index(Request $request)
    {
        // Get student's classroom
        $classroomId = Auth::user()->classroom_id;
        
        if (!$classroomId) {
            return view('siswa.assignments.index', [
                'assignments' => collect(), // Return empty collection instead of array
                'subjects' => collect(),
                'message' => 'You are not assigned to any classroom yet.'
            ]);
        }
        
        // Filter by status if provided
        $status = $request->input('status');
        
        // Get all assignments for the student's classroom
        $query = Assignment::where('classroom_id', $classroomId)
            ->with(['subject', 'submissions' => function($query) {
                $query->where('student_id', Auth::id());
            }]);
            
        // Filter by subject if provided
        if ($request->has('subject_id') && !empty($request->subject_id)) {
            $query->where('subject_id', $request->subject_id);
        }
        
        // Filter by status    
        if ($status === 'submitted') {
            $query->whereHas('submissions', function($query) {
                $query->where('student_id', Auth::id());
            });
        } elseif ($status === 'pending') {
            $query->whereDoesntHave('submissions', function($query) {
                $query->where('student_id', Auth::id());
            });
        } elseif ($status === 'expired') {
            $query->where('deadline', '<', now())
                ->whereDoesntHave('submissions', function($query) {
                    $query->where('student_id', Auth::id());
                });
        }
        
        $assignments = $query->latest()->paginate(10);
        
        // Get subjects for filter dropdown
        $subjects = Subject::whereHas('classrooms', function($query) use ($classroomId) {
            $query->where('classroom_id', $classroomId);
        })->get();
        
        return view('siswa.assignments.index', compact('assignments', 'status', 'subjects'));
    }
    
    /**
     * Show details of an assignment and submission form
     */
    public function show(Assignment $assignment)
    {
        // Verify assignment is for student's classroom
        if ($assignment->classroom_id !== Auth::user()->classroom_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $submission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', Auth::id())
            ->first();
            
        return view('siswa.assignments.show', compact('assignment', 'submission'));
    }
    
    /**
     * Submit a solution for an assignment
     */
    public function submit(Request $request, Assignment $assignment)
    {
        // Verify assignment is for student's classroom
        if ($assignment->classroom_id !== Auth::user()->classroom_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if deadline has passed
        if ($assignment->isExpired()) {
            return redirect()->route('siswa.assignments.show', $assignment)
                ->with('error', 'The deadline for this assignment has passed.');
        }
        
        $validated = $request->validate([
            'file' => ['required', 'file', 'max:10240'], // 10MB max
            'notes' => ['nullable', 'string'],
        ]);
        
        // Check if student already submitted
        $existingSubmission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', Auth::id())
            ->first();
            
        if ($existingSubmission) {
            // Delete old file if it exists
            if ($existingSubmission->file_path && Storage::exists($existingSubmission->file_path)) {
                Storage::delete($existingSubmission->file_path);
            }
            
            // Update existing submission
            $filePath = $request->file('file')->store('submissions');
            
            $existingSubmission->update([
                'file_path' => $filePath,
                'file_name' => $request->file('file')->getClientOriginalName(),
                'notes' => $validated['notes'] ?? null,
                'submitted_at' => now(),
            ]);
            
            return redirect()->route('siswa.assignments.show', $assignment)
                ->with('success', 'Assignment submission updated successfully.');
        }
        
        // Create new submission
        $filePath = $request->file('file')->store('submissions');
        
        Submission::create([
            'assignment_id' => $assignment->id,
            'student_id' => Auth::id(),
            'file_path' => $filePath,
            'file_name' => $request->file('file')->getClientOriginalName(),
            'notes' => $validated['notes'] ?? null,
            'submitted_at' => now(),
        ]);
        
        return redirect()->route('siswa.assignments.show', $assignment)
            ->with('success', 'Assignment submitted successfully.');
    }
}
