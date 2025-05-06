<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Material;
use App\Models\Subject;
use App\Models\Submission;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruDashboardController extends Controller
{
    /**
     * Display the guru dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $guru = Auth::user();
        
        // Subjects count
        $subjectsCount = $guru->teacherSubjects()->count();
        
        // Classes count - unique classrooms that the teacher teaches
        $classesCount = Classroom::whereHas('subjects', function($query) use ($guru) {
            $query->whereIn('subjects.id', $guru->teacherSubjects->pluck('id'));
        })->count();
        
        // Active assignments count
        $assignmentsCount = Assignment::where('teacher_id', $guru->id)
            ->where('deadline', '>=', now())
            ->count();
        
        // Materials count
        $materialsCount = Material::where('teacher_id', $guru->id)->count();
        
        // Statistics array
        $stats = [
            'subjects' => $subjectsCount,
            'classes' => $classesCount,
            'assignments' => $assignmentsCount,
            'materials' => $materialsCount,
        ];
        
        // Recent submissions
        $recentSubmissions = Submission::whereHas('assignment', function($query) use ($guru) {
            $query->where('teacher_id', $guru->id);
        })
        ->with(['student', 'assignment.subject', 'assignment.classroom'])
        ->latest()
        ->take(5)
        ->get();
        
        // Recent announcements
        $recentAnnouncements = Announcement::where(function($query) use ($guru) {
                $query->whereIn('audience', ['all', 'teachers'])
                      ->orWhere('author_id', $guru->id);
            })
            ->with('author')
            ->orderBy('is_important', 'desc')
            ->orderBy('publish_date', 'desc')
            ->take(3)
            ->get();
        
        return view('dashboard.guru', compact('stats', 'recentSubmissions', 'recentAnnouncements'));
    }

    /**
     * Refresh the guru dashboard data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $user = Auth::user();
        
        // Get updated statistics
        $stats = [
            'subjects' => $user->teacherSubjects()->count(),
            'classes' => $user->teachingClassrooms()->distinct()->count(),
            'assignments' => Assignment::where('created_by', $user->id)->count(),
            'materials' => Material::where('created_by', $user->id)->count(),
        ];
        
        // Get recent submissions
        $recentSubmissions = Submission::whereHas('assignment', function($query) use ($user) {
                $query->where('created_by', $user->id);
            })
            ->with(['student', 'assignment.classroom', 'assignment.subject'])
            ->orderBy('submitted_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($submission) {
                return [
                    'id' => $submission->id,
                    'assignment_id' => $submission->assignment_id,
                    'student' => [
                        'id' => $submission->student->id, 
                        'name' => $submission->student->name
                    ],
                    'assignment' => [
                        'id' => $submission->assignment->id,
                        'title' => $submission->assignment->title,
                        'classroom' => [
                            'id' => $submission->assignment->classroom->id,
                            'name' => $submission->assignment->classroom->name
                        ],
                        'subject' => [
                            'id' => $submission->assignment->subject->id,
                            'name' => $submission->assignment->subject->name
                        ]
                    ],
                    'submitted_at' => $submission->submitted_at,
                    'formatted_date' => $submission->submitted_at->format('d M, H:i'),
                    'score' => $submission->score,
                    'feedback' => $submission->feedback
                ];
            });
            
        // Get recent announcements
        $recentAnnouncements = Announcement::with('author')
            ->orderBy('publish_date', 'desc')
            ->take(5)
            ->get()
            ->map(function($announcement) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'content' => $announcement->content,
                    'publish_date' => $announcement->publish_date,
                    'formatted_date' => $announcement->publish_date->diffForHumans(),
                    'is_important' => $announcement->is_important,
                    'author_id' => $announcement->author_id,
                    'author' => $announcement->author ? [
                        'id' => $announcement->author->id,
                        'name' => $announcement->author->name
                    ] : null
                ];
            });
            
        // Return JSON response with updated data
        return response()->json([
            'stats' => $stats,
            'recentSubmissions' => $recentSubmissions,
            'recentAnnouncements' => $recentAnnouncements
        ]);
    }
}
