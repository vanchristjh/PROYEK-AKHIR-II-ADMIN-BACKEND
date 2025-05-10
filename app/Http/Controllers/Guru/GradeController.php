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
        $teacherId = $teacher->id;
        
        // Get teacher's subjects
        $subjects = $teacher->teacherSubjects()->get();
        
        // Get classrooms the teacher has access to
        $classrooms = $teacher->teachingClassrooms()->get();
        
        // Base query for direct grades
        $directGradesQuery = Grade::where('teacher_id', $teacherId);
        
        // Get submission-based grades
        $submissionsQuery = Submission::with(['student', 'assignment'])
                                ->whereHas('assignment', function($query) use ($teacherId) {
                                    $query->where('teacher_id', $teacherId);
                                });
        
        // Apply filters if provided
        if ($request->filled('subject')) {
            $subjectId = $request->subject;
            $directGradesQuery->where('subject_id', $subjectId);
            $submissionsQuery->whereHas('assignment', function($query) use ($subjectId) {
                $query->where('subject_id', $subjectId);
            });
        }
        
        if ($request->filled('classroom')) {
            $classroomId = $request->classroom;
            $directGradesQuery->where('classroom_id', $classroomId);
            $submissionsQuery->whereHas('student', function($query) use ($classroomId) {
                $query->where('classroom_id', $classroomId);
            });
        }
        
        // Get paginated results
        $directGrades = $directGradesQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'direct_page');
        $submissions = $submissionsQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'submissions_page');
        
        // Get all grades (mainly for backward compatibility)
        $grades = Grade::where('teacher_id', $teacherId)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10, ['*'], 'grades_page');
        
        // Pass all necessary variables to the view
        return view('guru.grades.index', compact(
            'grades', 
            'subjects', 
            'classrooms', 
            'submissions', 
            'directGrades'
        ));
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
        ], [
            'student_id.required' => 'Siswa harus dipilih.',
            'student_id.exists' => 'Siswa yang dipilih tidak valid.',
            'subject_id.required' => 'Mata pelajaran harus dipilih.',
            'subject_id.exists' => 'Mata pelajaran yang dipilih tidak valid.',
            'classroom_id.required' => 'Kelas harus dipilih.',
            'classroom_id.exists' => 'Kelas yang dipilih tidak valid.',
            'score.required' => 'Nilai harus diisi.',
            'score.numeric' => 'Nilai harus berupa angka.',
            'score.min' => 'Nilai tidak boleh kurang dari 0.',
            'max_score.required' => 'Nilai maksimum harus diisi.',
            'max_score.numeric' => 'Nilai maksimum harus berupa angka.',
            'max_score.min' => 'Nilai maksimum tidak boleh kurang dari 1.',
            'type.required' => 'Tipe penilaian harus diisi.',
            'semester.required' => 'Semester harus diisi.',
            'academic_year.required' => 'Tahun ajaran harus diisi.',
        ]);
        
        // Ensure teacher teaches this subject
        $teachesSubject = $teacher->teacherSubjects->contains($validated['subject_id']);
        if (!$teachesSubject) {
            return back()->withInput()->with('error', 'Anda tidak berwenang memberikan penilaian untuk mata pelajaran ini.');
        }
        
        // Ensure student belongs to selected classroom
        $student = User::findOrFail($validated['student_id']);
        if ($student->classroom_id != $validated['classroom_id']) {
            return back()->withInput()->with('error', 'Siswa tidak terdaftar di kelas yang dipilih.');
        }

        // Add teacher ID to the validated data
        $validated['teacher_id'] = $teacher->id;
        
        // Create the grade
        Grade::create($validated);
        
        return redirect()->route('guru.grades.index')
            ->with('success', 'Penilaian berhasil dibuat!');
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
            return back()->with('error', 'Anda tidak memiliki akses untuk mengedit nilai ini');
        }
        
        // If this grade is for an assignment, redirect to the submission page
        if ($grade->assignment_id) {
            // Find the submission
            $submission = Submission::where('student_id', $grade->student_id)
                ->where('assignment_id', $grade->assignment_id)
                ->first();
            
            if ($submission) {
                return redirect()->route('guru.submissions.show', [
                    'assignment' => $grade->assignment_id,
                    'submission' => $submission->id
                ]);
            }
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
        ], [
            'score.required' => 'Nilai harus diisi.',
            'score.numeric' => 'Nilai harus berupa angka.',
            'score.min' => 'Nilai tidak boleh kurang dari 0.',
            'score.max' => 'Nilai tidak boleh lebih dari ' . $grade->max_score . '.',
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
    
    /**
     * Get classrooms for a specific subject that the teacher teaches
     */
    public function getClassroomsBySubject($subjectId)
    {
        $teacher = Auth::user();
        
        // Check if teacher teaches this subject
        if (!$teacher->teacherSubjects->contains($subjectId)) {
            return response()->json([
                'error' => 'Anda tidak mengajar mata pelajaran ini.'
            ], 403);
        }
        
        // Get classrooms for this subject
        $classrooms = Classroom::whereHas('subjects', function($query) use ($subjectId) {
            $query->where('subjects.id', $subjectId);
        })->get(['id', 'name']);
        
        return response()->json($classrooms);
    }
    
    /**
     * Get students for a specific classroom
     */
    public function getStudentsByClassroom($classroomId)
    {
        $teacher = Auth::user();
        
        // Check if teacher teaches in this classroom
        $teachesInClassroom = Classroom::where('id', $classroomId)
            ->whereHas('subjects.teachers', function($query) use ($teacher) {
                $query->where('users.id', $teacher->id);
            })->exists();
            
        if (!$teachesInClassroom) {
            return response()->json([
                'error' => 'Anda tidak mengajar di kelas ini.'
            ], 403);
        }
        
        // Get students in this classroom
        $students = User::where('classroom_id', $classroomId)
            ->whereHas('role', function($query) {
                $query->where('slug', 'siswa');
            })
            ->orderBy('name')
            ->get(['id', 'name', 'nis']);
        
        return response()->json($students);
    }
}
