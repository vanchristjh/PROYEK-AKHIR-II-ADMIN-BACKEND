<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaDashboardController extends Controller
{
    /**
     * Display the siswa dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->classroom) {
            // Handle case where student is not assigned to a classroom
            $stats = [
                'subjects' => 0,
                'assignments' => 0,
                'completedAssignments' => 0,
                'materials' => 0,
                'attendanceRate' => '0%',
                'avgAssignmentScore' => '0',
                'completionRate' => '0%',
            ];
            
            return view('dashboard.siswa', compact('stats'));
        }
        
        // Get statistics
        $totalSubjects = $user->classroom->subjects()->count();
        $totalAssignments = Assignment::whereHas('classroom', function($query) use ($user) {
            $query->where('id', $user->classroom_id);
        })->count();
        
        $completedAssignments = Submission::where('student_id', $user->id)->count();
        
        $totalMaterials = Material::whereHas('subjects.classrooms', function($query) use ($user) {
            $query->where('classrooms.id', $user->classroom_id);
        })->count();
        
        // Calculate attendance rate
        $totalAttendanceRecords = Attendance::where('classroom_id', $user->classroom_id)->count();
        $userAttendancePresent = Attendance::where([
            'classroom_id' => $user->classroom_id,
            'student_id' => $user->id,
            'status' => 'present'
        ])->count();
        
        $attendanceRate = $totalAttendanceRecords > 0 ? 
            round(($userAttendancePresent / $totalAttendanceRecords) * 100) . '%' : '100%';
        
        // Calculate average assignment score
        $submissions = Submission::where('student_id', $user->id)
            ->whereNotNull('score')
            ->get();
            
        $avgScore = $submissions->count() > 0 ? 
            round($submissions->avg('score')) : 0;
        
        // Calculate completion rate
        $completionRate = $totalAssignments > 0 ? 
            round(($completedAssignments / $totalAssignments) * 100) . '%' : '0%';
        
        $stats = [
            'subjects' => $totalSubjects,
            'assignments' => $totalAssignments,
            'completedAssignments' => $completedAssignments,
            'materials' => $totalMaterials,
            'attendanceRate' => $attendanceRate,
            'avgAssignmentScore' => $avgScore,
            'completionRate' => $completionRate,
        ];
        
        // Get upcoming assignments
        $upcomingAssignments = Assignment::whereHas('classroom', function($query) use ($user) {
                $query->where('id', $user->classroom_id);
            })
            ->with(['subject'])
            ->where('deadline', '>=', now())
            ->orderBy('deadline', 'asc')
            ->take(5)
            ->get();
            
        // Get recent materials
        $recentMaterials = Material::whereHas('subjects.classrooms', function($query) use ($user) {
                $query->where('classrooms.id', $user->classroom_id);
            })
            ->with(['subject'])
            ->orderBy('publish_date', 'desc')
            ->take(3)
            ->get();
            
        // Get recent announcements
        $recentAnnouncements = Announcement::with('author')
            ->orderBy('publish_date', 'desc')
            ->take(5)
            ->get();
            
        return view('dashboard.siswa', compact(
            'stats', 
            'upcomingAssignments', 
            'recentMaterials', 
            'recentAnnouncements'
        ));
    }
    
    public function refresh()
    {
        $user = Auth::user();
        
        if (!$user->classroom) {
            // Handle case where student is not assigned to a classroom
            return response()->json([
                'stats' => [
                    'subjects' => 0,
                    'assignments' => 0,
                    'completedAssignments' => 0,
                    'materials' => 0,
                    'attendanceRate' => '0%',
                    'avgAssignmentScore' => '0',
                    'completionRate' => '0%',
                ],
                'upcomingAssignments' => [],
                'recentMaterials' => [],
                'recentAnnouncements' => []
            ]);
        }
        
        // Get statistics
        $totalSubjects = $user->classroom->subjects()->count();
        $totalAssignments = Assignment::whereHas('classroom', function($query) use ($user) {
            $query->where('id', $user->classroom_id);
        })->count();
        
        $completedAssignments = Submission::where('student_id', $user->id)->count();
        
        $totalMaterials = Material::whereHas('subjects.classrooms', function($query) use ($user) {
            $query->where('classrooms.id', $user->classroom_id);
        })->count();
        
        // Calculate attendance rate
        $totalAttendanceRecords = Attendance::where('classroom_id', $user->classroom_id)->count();
        $userAttendancePresent = Attendance::where([
            'classroom_id' => $user->classroom_id,
            'student_id' => $user->id,
            'status' => 'present'
        ])->count();
        
        $attendanceRate = $totalAttendanceRecords > 0 ? 
            round(($userAttendancePresent / $totalAttendanceRecords) * 100) . '%' : '100%';
        
        // Calculate average assignment score
        $submissions = Submission::where('student_id', $user->id)
            ->whereNotNull('score')
            ->get();
            
        $avgScore = $submissions->count() > 0 ? 
            round($submissions->avg('score')) : 0;
        
        // Calculate completion rate
        $completionRate = $totalAssignments > 0 ? 
            round(($completedAssignments / $totalAssignments) * 100) . '%' : '0%';
        
        $stats = [
            'subjects' => $totalSubjects,
            'assignments' => $totalAssignments,
            'completedAssignments' => $completedAssignments,
            'materials' => $totalMaterials,
            'attendanceRate' => $attendanceRate,
            'avgAssignmentScore' => $avgScore,
            'completionRate' => $completionRate,
        ];
        
        // Get upcoming assignments
        $upcomingAssignments = Assignment::whereHas('classroom', function($query) use ($user) {
                $query->where('id', $user->classroom_id);
            })
            ->with(['subject'])
            ->where('deadline', '>=', now())
            ->orderBy('deadline', 'asc')
            ->take(5)
            ->get()
            ->map(function($assignment) {
                $now = now();
                $deadline = $assignment->deadline;
                
                $remainingTime = '';
                $diffInDays = $deadline->diffInDays($now);
                
                if ($diffInDays > 0) {
                    $remainingTime = $diffInDays . ' hari lagi';
                } else {
                    $diffInHours = $deadline->diffInHours($now);
                    if ($diffInHours > 0) {
                        $remainingTime = $diffInHours . ' jam lagi';
                    } else {
                        $remainingTime = $deadline->diffInMinutes($now) . ' menit lagi';
                    }
                }
                
                return [
                    'id' => $assignment->id,
                    'title' => $assignment->title,
                    'description' => $assignment->description,
                    'deadline' => $assignment->deadline,
                    'remaining_time' => $remainingTime,
                    'subject' => [
                        'id' => $assignment->subject->id,
                        'name' => $assignment->subject->name
                    ]
                ];
            });
            
        // Get recent materials
        $recentMaterials = Material::whereHas('subjects.classrooms', function($query) use ($user) {
                $query->where('classrooms.id', $user->classroom_id);
            })
            ->with(['subject'])
            ->orderBy('publish_date', 'desc')
            ->take(3)
            ->get()
            ->map(function($material) {
                return [
                    'id' => $material->id,
                    'title' => $material->title,
                    'description' => $material->description,
                    'file' => $material->file,
                    'publish_date' => $material->publish_date,
                    'subject' => [
                        'id' => $material->subject->id,
                        'name' => $material->subject->name
                    ]
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
            'upcomingAssignments' => $upcomingAssignments,
            'recentMaterials' => $recentMaterials,
            'recentAnnouncements' => $recentAnnouncements
        ]);
    }
}
