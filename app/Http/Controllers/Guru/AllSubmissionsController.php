<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;

class AllSubmissionsController extends Controller
{
    /**
     * Display a listing of all submissions across all assignments.
     */
    public function index()
    {
        // Get the authenticated teacher
        $teacher = Auth::user();
        
        // Get all assignments created by this teacher
        $assignments = Assignment::where('teacher_id', $teacher->id)->pluck('id');
        
        // Get all submissions for those assignments
        $submissions = Submission::whereIn('assignment_id', $assignments)
            ->with(['assignment', 'student'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Count statistics
        $totalSubmissions = Submission::whereIn('assignment_id', $assignments)->count();
        $gradedCount = Submission::whereIn('assignment_id', $assignments)->whereNotNull('score')->count();
        $pendingCount = $totalSubmissions - $gradedCount;
        
        // Calculate average score for graded submissions
        $averageScore = Submission::whereIn('assignment_id', $assignments)
            ->whereNotNull('score')
            ->avg('score');
        
        return view('guru.submissions.all', compact(
            'submissions', 
            'totalSubmissions', 
            'gradedCount', 
            'pendingCount', 
            'averageScore'
        ));
    }
}
