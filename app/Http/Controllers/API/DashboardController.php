<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\AcademicCalendar;
use App\Models\Announcement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics and information
     */
    public function index()
    {
        // Get user role
        $user = auth()->user();
        $role = $user->role;
        
        // Basic stats
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_classes' => ClassRoom::count(),
            'events_this_month' => AcademicCalendar::whereMonth('start_date', Carbon::now()->month)
                ->whereYear('start_date', Carbon::now()->year)
                ->count(),
        ];
        
        // Get upcoming events
        $upcomingEvents = AcademicCalendar::where('start_date', '>', Carbon::now())
            ->where(function($q) use ($role) {
                $q->where('target_audience', 'all')
                  ->orWhere('target_audience', $role);
            })
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();
            
        // Get current/ongoing events
        $currentEvents = AcademicCalendar::where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->where(function($q) use ($role) {
                $q->where('target_audience', 'all')
                  ->orWhere('target_audience', $role);
            })
            ->orderBy('end_date', 'asc')
            ->get();
        
        // Get recent announcements
        $announcements = Announcement::active()
            ->where(function($q) use ($role) {
                $q->where('target_audience', 'all')
                  ->orWhere('target_audience', $role);
            })
            ->orderBy('priority', 'desc')
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($announcement) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'excerpt' => $announcement->excerpt,
                    'published_at' => $announcement->formatted_published_date,
                    'priority' => $announcement->priority,
                    'priority_badge' => strip_tags($announcement->priority_badge),
                ];
            });
        
        // Class data depends on role
        $classData = [];
        
        if ($role === 'student') {
            $classData = [
                'current_class' => $user->class ? [
                    'id' => $user->class->id,
                    'name' => $user->class->name,
                    'teacher' => $user->class->teacher ? $user->class->teacher->name : null,
                    'students_count' => $user->class->students->count(),
                ] : null,
            ];
        } elseif ($role === 'teacher') {
            $classData = [
                'classes_taught' => $user->classesTaught->map(function($class) {
                    return [
                        'id' => $class->id,
                        'name' => $class->name,
                        'students_count' => $class->students->count(),
                    ];
                }),
                'class_guardian' => $user->classGuardian ? [
                    'id' => $user->classGuardian->id,
                    'name' => $user->classGuardian->name,
                    'students_count' => $user->classGuardian->students->count(),
                ] : null,
            ];
        } elseif ($role === 'admin') {
            // Group classes by level
            $classGroups = [];
            $levels = ['X', 'XI', 'XII'];
            
            foreach ($levels as $level) {
                $classGroups[$level] = ClassRoom::where('level', $level)
                    ->withCount('students')
                    ->get()
                    ->map(function($class) {
                        return [
                            'id' => $class->id,
                            'name' => $class->name,
                            'students_count' => $class->students_count,
                            'teacher' => $class->teacher ? $class->teacher->name : null,
                        ];
                    });
            }
            
            $classData = [
                'class_groups' => $classGroups,
            ];
        }
        
        return response()->json([
            'success' => true,
            'stats' => $stats,
            'upcoming_events' => $upcomingEvents,
            'current_events' => $currentEvents,
            'announcements' => $announcements,
            'class_data' => $classData,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'avatar' => $user->profile_photo_url ?? null,
            ]
        ]);
    }
}
