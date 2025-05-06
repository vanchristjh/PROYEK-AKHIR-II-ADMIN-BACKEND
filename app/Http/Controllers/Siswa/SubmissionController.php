<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the student's submissions.
     */
    public function index()
    {
        $user = Auth::user();
        
        $submissions = Submission::where('student_id', $user->id)
                                ->with(['assignment', 'assignment.subject'])
                                ->latest('submitted_at')
                                ->get();
        
        return view('siswa.submissions.index', compact('submissions'));
    }

    /**
     * Display the specified submission.
     */
    public function show(Submission $submission)
    {
        // Check if the submission belongs to the authenticated student
        if ($submission->student_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('siswa.submissions.show', compact('submission'));
    }

    /**
     * Store a newly created submission in storage.
     */
    public function store(Request $request, Assignment $assignment)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max file size
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        // Check if the assignment is for the student's class
        if ($assignment->classroom_id !== $user->classroom_id) {
            return back()->with('error', 'Assignment not available for your class.');
        }

        // Check if student has already submitted
        $existingSubmission = Submission::where('assignment_id', $assignment->id)
                                        ->where('student_id', $user->id)
                                        ->first();
                                        
        if ($existingSubmission) {
            return back()->with('error', 'You have already submitted this assignment.');
        }

        // Check if the assignment deadline has passed
        if ($assignment->deadline < now()) {
            return back()->with('error', 'The deadline for this assignment has passed.');
        }

        // Handle file upload
        $path = $request->file('file')->store('submissions');

        // Create submission
        $submission = new Submission();
        $submission->assignment_id = $assignment->id;
        $submission->student_id = $user->id;
        $submission->file = $path;
        $submission->notes = $request->notes;
        $submission->submitted_at = now();
        $submission->save();

        return redirect()->route('siswa.assignments.show', $assignment)
                         ->with('success', 'Assignment submitted successfully.');
    }

    /**
     * Update an existing submission.
     */
    public function update(Request $request, Submission $submission)
    {
        $user = Auth::user();

        // Check if the submission belongs to the authenticated student
        if ($submission->student_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the assignment deadline has passed
        if ($submission->assignment->deadline < now()) {
            return back()->with('error', 'The deadline for this assignment has passed. You cannot update your submission.');
        }

        $request->validate([
            'file' => 'nullable|file|max:10240', // 10MB max file size
            'notes' => 'nullable|string|max:500',
        ]);

        // Update file if provided
        if ($request->hasFile('file')) {
            // Delete old file
            Storage::delete($submission->file);
            
            // Store new file
            $path = $request->file('file')->store('submissions');
            $submission->file = $path;
        }

        // Update notes
        if ($request->has('notes')) {
            $submission->notes = $request->notes;
        }

        $submission->submitted_at = now();
        $submission->save();

        return redirect()->route('siswa.submissions.show', $submission)
                         ->with('success', 'Submission updated successfully.');
    }
}
