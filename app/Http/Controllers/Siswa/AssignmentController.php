<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Subject;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class AssignmentController extends Controller
{
    /**
     * Display a listing of assignments for the student.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Check if the student is assigned to a classroom
        if (!$user->classroom) {
            return view('siswa.assignments.index', [
                'assignments' => new LengthAwarePaginator([], 0, 10),
                'message' => 'Anda tidak terdaftar dalam kelas manapun.'
            ]);
        }
        
        $classroomId = $user->classroom_id;
        
        // Filter by status if provided
        $status = $request->input('status');
        $subjectId = $request->input('subject_id');
        
        // Get all assignments for the student's classroom
        $query = Assignment::where('classroom_id', $classroomId)
            ->with(['subject', 'submissions' => function($query) use ($user) {
                $query->where('student_id', $user->id);
            }]);
        
        // Filter by status
        if ($status === 'submitted') {
            $query->whereHas('submissions', function($query) use ($user) {
                $query->where('student_id', $user->id);
            });
        } elseif ($status === 'pending') {
            $query->whereDoesntHave('submissions', function($query) use ($user) {
                $query->where('student_id', $user->id);
            });
        } elseif ($status === 'completed') {
            $query->whereHas('submissions', function($query) use ($user) {
                $query->where('student_id', $user->id)
                    ->whereNotNull('score');
            });
        }
        
        // Filter by subject if provided
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }
        
        $assignments = $query->latest()->paginate(10);
        
        // Get subjects for the filter
        $subjects = Subject::whereHas('classrooms', function($query) use ($classroomId) {
            $query->where('classrooms.id', $classroomId);
        })->get();
        
        return view('siswa.assignments.index', compact('assignments', 'status', 'subjects', 'subjectId'));
    }

    /**
     * Display the specified assignment.
     */
    public function show($id)
    {
        $assignment = Assignment::findOrFail($id);
        
        // Check if user's class has access to this assignment
        $student = Auth::user();
        $classroom = $student->classroom;
        
        // Check if user has submitted this assignment
        $submission = Submission::where('assignment_id', $id)
            ->where('student_id', $student->id)
            ->first();
        
        $isSubmitted = !is_null($submission);
        $isGraded = $isSubmitted && !is_null($submission->score);
        $isExpired = $assignment->deadline < now();
        
        return view('siswa.assignments.show', [
            'assignment' => $assignment,
            'submission' => $submission,
            'isSubmitted' => $isSubmitted,
            'isGraded' => $isGraded,
            'isExpired' => $isExpired,
        ]);
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
            return redirect()->route('siswa.assignments.show', $assignment)
                ->with('error', 'You have already submitted a solution for this assignment.');
        }
        
        // Store the file
        $filePath = $request->file('file')->store('submissions', 'public');
        
        // Create submission
        Submission::create([
            'assignment_id' => $assignment->id,
            'student_id' => Auth::id(),
            'file_path' => $filePath,
            'notes' => $validated['notes'],
            'submitted_at' => now(),
        ]);
        
        return redirect()->route('siswa.assignments.show', $assignment)
            ->with('success', 'Your solution has been submitted successfully!');
    }
    
    /**
     * Download the assignment file
     */
    public function download(Assignment $assignment)
    {
        // Verify assignment is for student's classroom
        if ($assignment->classroom_id !== Auth::user()->classroom_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if assignment has a file
        if (!$assignment->file_path) {
            abort(404, 'This assignment does not have an attached file.');
        }
        
        // Get original filename
        $filename = basename($assignment->file_path);
        
        // Return file download
        return Storage::disk('public')->download($assignment->file_path, $filename);
    }
    
    /**
     * Download the submission file
     */
    public function downloadSubmission(Submission $submission)
    {
        // Verify submission belongs to the student
        if ($submission->student_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Get original filename
        $filename = basename($submission->file_path);
        
        // Return file download
        return Storage::disk('public')->download($submission->file_path, $filename);
    }
}
