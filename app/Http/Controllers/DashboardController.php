<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\AcademicCalendar;
use App\Models\Announcement;
use App\Models\AttendanceRecord;
use App\Models\TeacherAttendanceRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
        
        // Calculate student attendance statistics
        $studentAttendanceSummary = $this->getStudentAttendanceStats();
        
        // Calculate teacher attendance statistics
        $teacherAttendanceSummary = $this->getTeacherAttendanceStats();
        
        // Get unread notification count
        $unreadNotifications = Auth::user()->notifications()->unread()->count();

        return view('dashboard.dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalClasses',
            'upcomingEvents',
            'currentEvents',
            'eventsThisMonth',
            'classGroups',
            'announcements',
            'studentAttendanceSummary',
            'teacherAttendanceSummary',
            'unreadNotifications'
        ));
    }

    /**
     * Get student attendance statistics
     */
    private function getStudentAttendanceStats()
    {
        // Get total attendance records
        $totalRecords = AttendanceRecord::count();
        
        if ($totalRecords === 0) {
            return [
                'total_records' => 0,
                'attendance_percentage' => '0%',
                'present_percentage' => '0%',
                'absent_percentage' => '0%',
                'statuses' => [
                    'hadir' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'alpa' => 0,
                    'terlambat' => 0,
                ]
            ];
        }

        // Count records by status
        $presentCount = AttendanceRecord::where('status', 'hadir')->count();
        $izinCount = AttendanceRecord::where('status', 'izin')->count();
        $sakitCount = AttendanceRecord::where('status', 'sakit')->count();
        $alpaCount = AttendanceRecord::where('status', 'alpa')->count();
        $terlambatCount = AttendanceRecord::where('status', 'terlambat')->count();
        
        // Calculate total absences (izin + sakit + alpa)
        $absentCount = $izinCount + $sakitCount + $alpaCount;
        
        // Calculate percentages
        $presentPercentage = round(($presentCount / $totalRecords) * 100);
        $absentPercentage = round((($absentCount) / $totalRecords) * 100);
        
        return [
            'total_records' => $totalRecords,
            'attendance_percentage' => $presentPercentage . '%',
            'present_percentage' => $presentPercentage . '%',
            'absent_percentage' => $absentPercentage . '%',
            'statuses' => [
                'hadir' => $presentCount,
                'izin' => $izinCount,
                'sakit' => $sakitCount,
                'alpa' => $alpaCount,
                'terlambat' => $terlambatCount,
            ]
        ];
    }

    /**
     * Get teacher attendance statistics
     */
    private function getTeacherAttendanceStats()
    {
        // Get total attendance records
        $totalRecords = TeacherAttendanceRecord::count();
        
        if ($totalRecords === 0) {
            return [
                'total_records' => 0,
                'attendance_percentage' => '0%',
                'present_percentage' => '0%',
                'absent_percentage' => '0%',
                'statuses' => [
                    'hadir' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'alpa' => 0,
                    'terlambat' => 0,
                ]
            ];
        }

        // Count records by status
        $presentCount = TeacherAttendanceRecord::where('status', 'hadir')->count();
        $izinCount = TeacherAttendanceRecord::where('status', 'izin')->count();
        $sakitCount = TeacherAttendanceRecord::where('status', 'sakit')->count();
        $alpaCount = TeacherAttendanceRecord::where('status', 'alpa')->count();
        $terlambatCount = TeacherAttendanceRecord::where('status', 'terlambat')->count();
        
        // Calculate total absences (izin + sakit + alpa)
        $absentCount = $izinCount + $sakitCount + $alpaCount;
        
        // Calculate percentages
        $presentPercentage = round(($presentCount / $totalRecords) * 100);
        $absentPercentage = round(($absentCount / $totalRecords) * 100);
        
        return [
            'total_records' => $totalRecords,
            'attendance_percentage' => $presentPercentage . '%',
            'present_percentage' => $presentPercentage . '%',
            'absent_percentage' => $absentPercentage . '%',
            'statuses' => [
                'hadir' => $presentCount,
                'izin' => $izinCount,
                'sakit' => $sakitCount,
                'alpa' => $alpaCount,
                'terlambat' => $terlambatCount,
            ]
        ];
    }
}