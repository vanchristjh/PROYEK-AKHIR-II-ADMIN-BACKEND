<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\Announcement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function index()
    {
        // Get role IDs
        $adminRole = Role::where('slug', 'admin')->first();
        $teacherRole = Role::where('slug', 'guru')->first();
        $studentRole = Role::where('slug', 'siswa')->first();
        
        // Get counts for cards
        $studentCount = User::where('role_id', $studentRole->id ?? 0)->count();
        $teacherCount = User::where('role_id', $teacherRole->id ?? 0)->count();
        $classroomCount = Classroom::count();
        $subjectCount = Subject::count();
        
        // Get recent users
        $recentUsers = User::with('role')
                        ->latest()
                        ->take(5)
                        ->get();
        
        // Get recent announcements
        $recentAnnouncements = Announcement::with('author')
                        ->latest('publish_date')
                        ->take(3)
                        ->get();
        
        // Define colors and icons for activity lists
        $colors = ['blue', 'indigo', 'cyan', 'emerald', 'amber', 'purple', 'rose'];
        $icons = [
            'user_created' => 'user-plus',
            'user_updated' => 'user-edit',
            'user_deleted' => 'user-minus',
            'class_created' => 'school',
            'class_updated' => 'chalkboard',
            'class_deleted' => 'trash-alt',
            'subject_created' => 'book',
            'subject_updated' => 'edit',
            'subject_deleted' => 'trash',
            'login' => 'sign-in-alt',
            'logout' => 'sign-out-alt',
        ];
        
        return view('dashboard.admin', compact(
            'studentCount',
            'teacherCount',
            'classroomCount',
            'subjectCount',
            'recentUsers',
            'recentAnnouncements',
            'colors',
            'icons'
        ));
    }
}
