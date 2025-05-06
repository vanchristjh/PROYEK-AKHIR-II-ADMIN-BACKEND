<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    /**
     * Display a listing of the student's grades.
     */
    public function index()
    {
        $user = Auth::user();
        
        $submissions = Submission::where('student_id', $user->id)
            ->with(['assignment', 'assignment.subject'])
            ->whereNotNull('score')
            ->latest('updated_at')
            ->get();

        // Group submissions by subject
        $gradesBySubject = $submissions->groupBy('assignment.subject.name');

        // Calculate averages per subject
        $averageBySubject = [];
        foreach ($gradesBySubject as $subject => $submissions) {
            $averageBySubject[$subject] = $submissions->avg('score');
        }

        // Calculate overall average
        $overallAverage = $submissions->count() > 0 ? $submissions->avg('score') : 0;
        
        return view('siswa.grades.index', compact('gradesBySubject', 'averageBySubject', 'overallAverage'));
    }
}
