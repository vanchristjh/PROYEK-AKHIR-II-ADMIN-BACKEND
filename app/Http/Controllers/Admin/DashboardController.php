<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Announcement;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get counts for summary cards
        $studentsCount = User::where('role_id', 3)->count();  // Assuming role_id 3 is for students
        $teachersCount = User::where('role_id', 2)->count();  // Assuming role_id 2 is for teachers
        $classroomsCount = Classroom::count();
        $subjectsCount = Subject::count();
        
        // Get recent users (limit to 5)
        $recentUsers = User::with('role')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
          // Get recent teachers with subjects count (limit to 4)
        $recentTeachers = User::where('role_id', 2)
            ->selectRaw('users.*, (select count(*) from subjects inner join subject_teacher on subjects.id = subject_teacher.subject_id where subject_teacher.teacher_id = users.id) as subjects_count')
            ->with(['subjects'])
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Get recent students with their classrooms (limit to 4)
        $recentStudents = User::with(['classroom' => function($query) {
                $query->orderBy('name');
            }])
            ->where('role_id', 3)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Get recent classrooms with students count (limit to 4)
        $recentClassrooms = Classroom::with(['students', 'homeroomTeacher'])
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Get recent announcements (limit to 5)
        $recentAnnouncements = Announcement::with('author')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'studentsCount', 
            'teachersCount', 
            'classroomsCount', 
            'subjectsCount',
            'recentUsers',
            'recentTeachers',
            'recentStudents',
            'recentClassrooms',
            'recentAnnouncements'
        ));
    }

    /**
     * Get dashboard statistics as JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats()
    {
        $studentsCount = User::where('role_id', 3)->count();
        $teachersCount = User::where('role_id', 2)->count();
        $classroomsCount = Classroom::count();
        $subjectsCount = Subject::count();
        
        return response()->json([
            'studentsCount' => $studentsCount,
            'teachersCount' => $teachersCount,
            'classroomsCount' => $classroomsCount,
            'subjectsCount' => $subjectsCount,
        ]);
    }

    /**
     * Get recent activities for the dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivities()
    {
        // You can implement this to return recent activities
        // For example, recent logins, new users, etc.
        return response()->json([
            'activities' => []
        ]);
    }
}
