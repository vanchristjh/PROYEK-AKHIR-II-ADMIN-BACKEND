<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AcademicCalendar;

class DashboardController extends Controller
{
    public function index()
    {
        // Count statistics
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        
        // Get upcoming events
        $upcomingEvents = AcademicCalendar::upcoming()->take(5)->get();
        $currentEvents = AcademicCalendar::current()->take(3)->get();
        
        $session = session()->all(); // or another appropriate definition

        return view('dashboard.dashboard', compact('totalStudents', 'totalTeachers', 'upcomingEvents', 'currentEvents', 'session'));
    }
}