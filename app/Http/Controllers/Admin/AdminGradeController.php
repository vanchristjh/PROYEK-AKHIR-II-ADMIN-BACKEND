<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\User;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminGradeController extends Controller
{
    /**
     * Display a listing of grades with powerful filtering options for admin
     */
    public function index(Request $request)
    {
        $query = Grade::with(['student', 'teacher', 'subject', 'classroom']);
        
        // Advanced filtering options for admin
        if ($request->filled('classroom')) {
            $query->where('classroom_id', $request->classroom);
        }
        
        if ($request->filled('subject')) {
            $query->where('subject_id', $request->subject);
        }
        
        if ($request->filled('student')) {
            $query->where('student_id', $request->student);
        }
        
        if ($request->filled('teacher')) {
            $query->where('teacher_id', $request->teacher);
        }
        
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }
        
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }
        
        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $grades = $query->paginate(20)->withQueryString();
        
        // Get data for filters
        $classrooms = Classroom::all();
        $subjects = Subject::all();
        $teachers = User::where('role_id', 2)->get(); // Assuming 2 is teacher role_id
        $students = User::where('role_id', 3)->get(); // Assuming 3 is student role_id
        
        return view('admin.grades.index', compact(
            'grades', 'classrooms', 'subjects', 'teachers', 'students'
        ));
    }
    
    /**
     * Admin can view detailed grade reports
     */
    public function show(Grade $grade)
    {
        $grade->load(['student', 'teacher', 'subject', 'classroom', 'assignment']);
        
        // Get student's grades history in this subject
        $studentHistory = Grade::where('student_id', $grade->student_id)
            ->where('subject_id', $grade->subject_id)
            ->where('id', '!=', $grade->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.grades.show', compact('grade', 'studentHistory'));
    }
    
    /**
     * Admin can override or adjust grades if needed
     */
    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:' . $grade->max_score,
            'feedback' => 'nullable|string',
            'admin_notes' => 'nullable|string',
        ]);
        
        // Record the admin modification
        $validated['modified_by_admin'] = true;
        $validated['admin_id'] = auth()->id();
        
        $grade->update($validated);
        
        return redirect()->route('admin.grades.show', $grade)
            ->with('success', 'Grade has been updated successfully');
    }
    
    /**
     * Generate reports for grades
     */
    public function reports(Request $request)
    {
        // Class average by subject
        $classAverages = DB::table('grades')
            ->select('classroom_id', 'subject_id', 
                DB::raw('AVG(score/max_score * 100) as average_percentage'))
            ->groupBy('classroom_id', 'subject_id')
            ->get();
            
        // Overall student performance
        $studentPerformance = DB::table('grades')
            ->select('student_id', 
                DB::raw('AVG(score/max_score * 100) as average_percentage'),
                DB::raw('COUNT(*) as grade_count'))
            ->groupBy('student_id')
            ->orderByDesc('average_percentage')
            ->get();
            
        // Additional reports...
        
        return view('admin.grades.reports', compact(
            'classAverages', 'studentPerformance'
        ));
    }
}
