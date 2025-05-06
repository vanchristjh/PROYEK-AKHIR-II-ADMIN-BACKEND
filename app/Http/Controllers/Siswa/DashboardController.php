<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Announcement;
use App\Models\Material;
use App\Models\SubmittedAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the dashboard for students
     */
    public function index()
    {
        // Get student data
        $student = Auth::user();
        
        // Count stats
        $stats = [
            'subjects' => $student->classroom ? $student->classroom->subjects()->count() : 0,
            'assignments' => $student->classroom ? Assignment::whereHas('subject.classrooms', function ($query) use ($student) {
                $query->where('classrooms.id', $student->classroom_id);
            })->count() : 0,
            'completedAssignments' => $student->submittedAssignments()->count(),
            'materials' => $student->classroom ? Material::whereHas('subject.classrooms', function ($query) use ($student) {
                $query->where('classrooms.id', $student->classroom_id);
            })->count() : 0,
        ];
        
        // Get upcoming/pending assignments
        $upcomingAssignments = $student->classroom ? Assignment::with('subject')
            ->whereHas('subject.classrooms', function ($query) use ($student) {
                $query->where('classrooms.id', $student->classroom_id);
            })
            ->whereNotIn('id', function ($query) use ($student) {
                $query->select('assignment_id')
                    ->from('submitted_assignments')
                    ->where('student_id', $student->id);
            })
            ->orderBy('deadline')
            ->take(3)
            ->get() : collect();
            
        // Get recent materials
        $recentMaterials = $student->classroom ? Material::with('subject')
            ->whereHas('subject.classrooms', function ($query) use ($student) {
                $query->where('classrooms.id', $student->classroom_id);
            })
            ->latest('publish_date')
            ->take(3)
            ->get() : collect();
            
        // Get recent announcements for students
        $recentAnnouncements = Announcement::with('author')
            ->visibleToRole('siswa')
            ->active()
            ->latest('publish_date')
            ->take(3)
            ->get();
            
        return view('dashboard.siswa', compact('stats', 'upcomingAssignments', 'recentMaterials', 'recentAnnouncements'));
    }
}