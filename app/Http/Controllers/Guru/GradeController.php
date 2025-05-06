<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Submission;
use App\Models\User;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    /**
     * Display grades for assignments and direct assessments
     */
    public function index(Request $request)
    {
        $teacher = Auth::user();
        
        // Query submissions instead of grades
        $query = Submission::with(['student', 'assignment.subject', 'assignment.classroom'])
            ->whereHas('assignment', function($q) use($teacher) {
                $q->whereHas('subject.teachers', function($q2) use($teacher) {
                    $q2->where('user_id', $teacher->id);
                });
            });
            
        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'graded') {
                $query->whereNotNull('score');
            } elseif ($request->status === 'ungraded') {
                $query->whereNull('score');
            }
        }
        
        // Filter by subject
        if ($request->filled('subject')) {
            $query->whereHas('assignment.subject', function($q) use($request) {
                $q->where('id', $request->subject);
            });
        }
        
        // Filter by classroom
        if ($request->filled('classroom')) {
            $query->whereHas('assignment.classroom', function($q) use($request) {
                $q->where('id', $request->classroom);
            });
        }
        
        $submissions = $query->latest('submitted_at')->paginate(15);
        
        // Get subjects that this teacher teaches
        $subjects = $teacher->teacherSubjects;
        
        // Get classrooms that this teacher teaches
        $classrooms = Classroom::whereHas('subjects.teachers', function($query) use ($teacher) {
            $query->where('users.id', $teacher->id);
        })->get();
        
        return view('guru.grades.index', compact('submissions', 'subjects', 'classrooms'));
    }
    
    /**
     * Display form to create a grade outside of an assignment (direct assessment)
     */
    public function create()
    {
        $teacher = Auth::user();
        $subjects = $teacher->teacherSubjects;
        $classrooms = Classroom::whereHas('subjects.teachers', function($query) use ($teacher) {
            $query->where('users.id', $teacher->id);
        })->get();
        
        return view('guru.grades.create', compact('subjects', 'classrooms'));
    }
    
    /**
     * Store a new direct assessment grade
     */
    public function store(Request $request)
    {
        $teacher = Auth::user();
        
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|min:1',
            'type' => 'required|string',
            'feedback' => 'nullable|string',
            'semester' => 'required|string',
            'academic_year' => 'required|string',
        ]);
        
        // Ensure teacher teaches this subject
        if (!$teacher->subjects->contains($validated['subject_id'])) {
            return back()->with('error', 'You cannot grade for this subject.');
        }
        
        $validated['teacher_id'] = $teacher->id;
        
        Grade::create($validated);
        
        return redirect()->route('guru.grades.index')
            ->with('success', 'Grade created successfully');
    }
    
    /**
     * Grade an assignment submission
     */
    public function gradeSubmission(Request $request, Submission $submission)
    {
        $teacher = Auth::user();
        $assignment = $submission->assignment;
        
        // Check if teacher is authorized to grade this submission
        if ($assignment->teacher_id !== $teacher->id) {
            return back()->with('error', 'You are not authorized to grade this submission');
        }
        
        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:' . $assignment->max_score,
            'feedback' => 'nullable|string',
        ]);
        
        // Update the submission with grade data
        $submission->update([
            'score' => $validated['score'],
            'feedback' => $validated['feedback'],
            'graded_at' => now(),
        ]);
        
        // Create or update corresponding grade record
        Grade::updateOrCreate(
            [
                'student_id' => $submission->student_id,
                'assignment_id' => $assignment->id,
            ],
            [
                'teacher_id' => $teacher->id,
                'subject_id' => $assignment->subject_id,
                'classroom_id' => $assignment->classroom_id,
                'score' => $validated['score'],
                'max_score' => $assignment->max_score,
                'type' => 'assignment',
                'feedback' => $validated['feedback'],
                'semester' => getCurrentSemester(), // Helper function
                'academic_year' => getCurrentAcademicYear(), // Helper function
            ]
        );
        
        return back()->with('success', 'Submission graded successfully');
    }
    
    /**
     * Edit an existing grade
     */
    public function edit(Grade $grade)
    {
        $teacher = Auth::user();
        
        // Check if teacher owns this grade
        if ($grade->teacher_id !== $teacher->id) {
            return back()->with('error', 'You are not authorized to edit this grade');
        }
        
        return view('guru.grades.edit', compact('grade'));
    }
    
    /**
     * Update an existing grade
     */
    public function update(Request $request, Grade $grade)
    {
        $teacher = Auth::user();
        
        // Check if teacher owns this grade
        if ($grade->teacher_id !== $teacher->id) {
            return back()->with('error', 'You are not authorized to update this grade');
        }
        
        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:' . $grade->max_score,
            'feedback' => 'nullable|string',
        ]);
        
        $grade->update($validated);
        
        // If this is an assignment grade, update the submission too
        if ($grade->assignment_id) {
            $submission = Submission::where('student_id', $grade->student_id)
                ->where('assignment_id', $grade->assignment_id)
                ->first();
                
            if ($submission) {
                $submission->update([
                    'score' => $validated['score'],
                    'feedback' => $validated['feedback'],
                ]);
            }
        }
        
        return redirect()->route('guru.grades.index')
            ->with('success', 'Grade updated successfully');
    }
    
    /**
     * Delete a grade
     */
    public function destroy(Grade $grade)
    {
        $teacher = Auth::user();
        
        // Check if teacher owns this grade
        if ($grade->teacher_id !== $teacher->id) {
            return back()->with('error', 'You are not authorized to delete this grade');
        }
        
        $grade->delete();
        
        return redirect()->route('guru.grades.index')
            ->with('success', 'Grade deleted successfully');
    }
}
