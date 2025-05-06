<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiswaGradeController extends Controller
{
    /**
     * Display all grades for the student
     */
    public function index(Request $request)
    {
        $student = Auth::user();
        $query = Grade::with(['subject', 'teacher'])
            ->where('student_id', $student->id);
            
        // Filter by subject
        if ($request->filled('subject')) {
            $query->where('subject_id', $request->subject);
        }
        
        // Filter by semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }
        
        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }
            
        $grades = $query->latest()->get();
        
        // Group grades by subject for better display
        $gradesBySubject = $grades->groupBy('subject_id');
        
        // Calculate averages per subject
        $subjectAverages = [];
        foreach ($gradesBySubject as $subjectId => $subjectGrades) {
            $totalPercentage = 0;
            $count = 0;
            
            foreach ($subjectGrades as $grade) {
                $totalPercentage += ($grade->score / $grade->max_score) * 100;
                $count++;
            }
            
            $average = $count > 0 ? $totalPercentage / $count : 0;
            $subjectAverages[$subjectId] = round($average, 1);
        }
        
        // Get distinct semesters and academic years for filters
        $semesters = Grade::where('student_id', $student->id)
            ->select('semester')
            ->distinct()
            ->pluck('semester');
            
        $academicYears = Grade::where('student_id', $student->id)
            ->select('academic_year')
            ->distinct()
            ->pluck('academic_year');
        
        // Get subjects for this student's class
        $subjects = Subject::whereHas('classrooms', function($query) use ($student) {
            $query->where('classrooms.id', $student->classroom_id);
        })->get();
        
        return view('siswa.grades.index', compact(
            'grades', 'gradesBySubject', 'subjectAverages', 
            'subjects', 'semesters', 'academicYears'
        ));
    }
    
    /**
     * Show detailed view of a specific grade
     */
    public function show(Grade $grade)
    {
        $student = Auth::user();
        
        // Ensure the grade belongs to the student
        if ($grade->student_id !== $student->id) {
            return redirect()->route('unauthorized');
        }
        
        // Load related data
        $grade->load(['subject', 'teacher', 'assignment']);
        
        // Get other grades in same subject for comparison
        $relatedGrades = Grade::where('student_id', $student->id)
            ->where('subject_id', $grade->subject_id)
            ->where('id', '!=', $grade->id)
            ->latest()
            ->get();
            
        // Calculate performance trend
        $performanceTrend = $this->calculatePerformanceTrend($student->id, $grade->subject_id);
        
        return view('siswa.grades.show', compact('grade', 'relatedGrades', 'performanceTrend'));
    }
    
    /**
     * Calculate student's performance trend for a subject
     */
    private function calculatePerformanceTrend($studentId, $subjectId)
    {
        $grades = Grade::where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->orderBy('created_at')
            ->get();
            
        if ($grades->count() < 2) {
            return 'not_enough_data';
        }
        
        $percentages = $grades->map(function($grade) {
            return ($grade->score / $grade->max_score) * 100;
        });
        
        $firstHalf = $percentages->take(floor($percentages->count() / 2))->avg();
        $secondHalf = $percentages->skip(floor($percentages->count() / 2))->avg();
        
        $difference = $secondHalf - $firstHalf;
        
        if ($difference > 5) return 'improving';
        if ($difference < -5) return 'declining';
        return 'stable';
    }
    
    /**
     * Display student's grades report
     */
    public function report()
    {
        $student = Auth::user();
        
        // Overall student grade statistics
        $overallStats = DB::table('grades')
            ->where('student_id', $student->id)
            ->select(
                DB::raw('AVG(score/max_score * 100) as average_percentage'),
                DB::raw('MIN(score/max_score * 100) as min_percentage'),
                DB::raw('MAX(score/max_score * 100) as max_percentage')
            )
            ->first();
            
        // Subject-wise performance
        $subjectPerformance = DB::table('grades')
            ->where('student_id', $student->id)
            ->join('subjects', 'grades.subject_id', '=', 'subjects.id')
            ->select(
                'subjects.name as subject_name',
                DB::raw('AVG(score/max_score * 100) as average_percentage'),
                DB::raw('COUNT(*) as grade_count')
            )
            ->groupBy('subjects.name')
            ->get();
            
        // Grade distribution
        $gradeDistribution = [];
        $grades = Grade::where('student_id', $student->id)->get();
        foreach ($grades as $grade) {
            $percentage = ($grade->score / $grade->max_score) * 100;
            $letterGrade = '';
            
            if ($percentage >= 90) $letterGrade = 'A';
            elseif ($percentage >= 80) $letterGrade = 'B';
            elseif ($percentage >= 70) $letterGrade = 'C';
            elseif ($percentage >= 60) $letterGrade = 'D';
            else $letterGrade = 'E';
            
            if (!isset($gradeDistribution[$letterGrade])) {
                $gradeDistribution[$letterGrade] = 0;
            }
            $gradeDistribution[$letterGrade]++;
        }
        
        return view('siswa.grades.report', compact(
            'overallStats', 'subjectPerformance', 'gradeDistribution'
        ));
    }
}
