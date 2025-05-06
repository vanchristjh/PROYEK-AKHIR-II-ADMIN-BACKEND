<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Material;
use App\Models\Subject;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        
        // Get statistics for teacher
        $stats = [
            'subjects' => Subject::whereHas('teachers', function($query) use ($teacher) {
                $query->where('user_id', $teacher->id);
            })->count(),
            'classes' => Classroom::whereHas('subjects.teachers', function($query) use ($teacher) {
                $query->where('user_id', $teacher->id);
            })->distinct()->count(),
            'materials' => Material::whereHas('subject.teachers', function($query) use ($teacher) {
                $query->where('user_id', $teacher->id);
            })->count(),
            'students' => User::whereHas('roles', function($query) {
                $query->where('name', 'Siswa');
            })->count(),
            'assignments' => Assignment::whereHas('subject.teachers', function($query) use ($teacher) {
                $query->where('user_id', $teacher->id);
            })->count(),
        ];
        
        // Get recent submissions
        $recentSubmissions = Submission::with(['student', 'assignment.subject'])
            ->whereHas('assignment.subject.teachers', function($query) use ($teacher) {
                $query->where('user_id', $teacher->id);
            })
            ->latest('submitted_at')
            ->take(3)
            ->get();
        
        // Get recent announcements for teachers
        $recentAnnouncements = Announcement::with('author')
            ->visibleToRole('guru')
            ->active()
            ->latest('publish_date')
            ->take(3)
            ->get();
            
        return view('dashboard.guru', compact('stats', 'recentSubmissions', 'recentAnnouncements'));
    }
}