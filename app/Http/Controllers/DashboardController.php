<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\AcademicCalendar;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total counts for stats cards
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalClasses = ClassRoom::count();
        
        // Get upcoming events
        $upcomingEvents = AcademicCalendar::where('start_date', '>', Carbon::now())
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();
            
        // Get current/ongoing events
        $currentEvents = AcademicCalendar::where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->orderBy('end_date', 'asc')
            ->get();
            
        // Count events for this month
        $eventsThisMonth = AcademicCalendar::whereMonth('start_date', Carbon::now()->month)
            ->whereYear('start_date', Carbon::now()->year)
            ->count();
        
        // Get all classes and group by level
        $classes = ClassRoom::with('teacher', 'students')
            ->orderBy('name')
            ->get();
            
        $classGroups = [
            'X' => $classes->where('level', 'X'),
            'XI' => $classes->where('level', 'XI'),
            'XII' => $classes->where('level', 'XII'),
        ];
        
        // Get announcements if the model exists
        $announcements = [];
        if (class_exists('\App\Models\Announcement')) {
            $announcements = Announcement::orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        }
        
        return view('dashboard.dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalClasses',
            'upcomingEvents',
            'currentEvents',
            'eventsThisMonth',
            'classGroups',
            'announcements'
        ));
    }
}