<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassRoom;
use App\Models\User;
use App\Models\Subject;
use App\Models\GradeItem;
use App\Models\StudentGrade;

class AcademicReportController extends Controller
{
    /**
     * Display a listing of academic reports.
     */
    public function index(Request $request)
    {
        $classes = ClassRoom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        
        $students = collect();
        $selectedClass = null;
        
        if ($request->filled('class_id')) {
            $selectedClass = ClassRoom::find($request->class_id);
            $students = User::where('role', 'student')
                ->where('class_id', $request->class_id)
                ->orderBy('name')
                ->get();
        }
        
        return view('dashboard.grades.reports.index', compact('classes', 'subjects', 'students', 'selectedClass'));
    }
    
    /**
     * Display report for a specific student
     */
    public function show($id)
    {
        $student = User::findOrFail($id);
        
        if ($student->role !== 'student') {
            return redirect()->route('academic-reports.index')
                ->with('error', 'Data siswa tidak ditemukan');
        }
        
        $subjects = Subject::orderBy('name')->get();
        $academicYear = request('academic_year', date('Y') . '/' . (date('Y') + 1));
        $semester = request('semester', '1');
        
        // Get student grades
        $grades = StudentGrade::with(['gradeItem.category', 'gradeItem.subject'])
            ->whereHas('gradeItem', function ($query) use ($academicYear, $semester) {
                $query->where('academic_year', $academicYear)
                      ->where('semester', $semester);
            })
            ->where('student_id', $student->id)
            ->get();
            
        // Group grades by subject
        $gradesBySubject = $grades->groupBy('gradeItem.subject_id');
        
        return view('dashboard.grades.reports.show', compact(
            'student', 
            'subjects', 
            'gradesBySubject',
            'academicYear',
            'semester'
        ));
    }
    
    /**
     * Print the academic report
     */
    public function print($id)
    {
        $student = User::findOrFail($id);
        
        if ($student->role !== 'student') {
            return redirect()->route('academic-reports.index')
                ->with('error', 'Data siswa tidak ditemukan');
        }
        
        $academicYear = request('academic_year', date('Y') . '/' . (date('Y') + 1));
        $semester = request('semester', '1');
        
        // Get student grades
        $grades = StudentGrade::with(['gradeItem.category', 'gradeItem.subject'])
            ->whereHas('gradeItem', function ($query) use ($academicYear, $semester) {
                $query->where('academic_year', $academicYear)
                      ->where('semester', $semester);
            })
            ->where('student_id', $student->id)
            ->get();
            
        // Group grades by subject
        $gradesBySubject = $grades->groupBy('gradeItem.subject_id');
        $subjects = Subject::whereIn('id', $gradesBySubject->keys())->get();
        
        return view('dashboard.grades.reports.print', compact(
            'student', 
            'subjects', 
            'gradesBySubject',
            'academicYear',
            'semester'
        ));
    }
}
