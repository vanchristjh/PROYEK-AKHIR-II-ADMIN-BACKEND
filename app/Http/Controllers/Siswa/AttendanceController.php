<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceRecord;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display the attendance index/summary page
     */
    public function index()
    {
        $student = auth()->user();
        
        // Get current month attendance records
        $currentMonth = now()->format('m');
        $currentYear = now()->format('Y');
        
        // Get all subjects the student is enrolled in
        $subjects = $student->classroom->subjects ?? collect();
        
        // Get attendance summary for current month
        $attendanceSummary = $this->getAttendanceSummary($student->id, $currentMonth, $currentYear);
        
        // Recent attendance records
        $recentAttendance = AttendanceRecord::where('student_id', $student->id)
            ->with(['attendance.subject', 'attendance.teacher'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('siswa.attendance.index', compact('student', 'subjects', 'attendanceSummary', 'recentAttendance'));
    }
    
    /**
     * Display monthly attendance
     */
    public function month($month = null, $year = null)
    {
        $student = auth()->user();
        
        $month = $month ?? now()->format('m');
        $year = $year ?? now()->format('Y');
        
        // Get attendance records for specified month
        $attendanceRecords = AttendanceRecord::whereHas('attendance', function($query) use ($month, $year) {
                $query->whereMonth('date', $month)
                      ->whereYear('date', $year);
            })
            ->where('student_id', $student->id)
            ->with(['attendance.subject', 'attendance.teacher'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($record) {
                return $record->attendance->date->format('Y-m-d');
            });
        
        $dateObj = Carbon::createFromDate($year, $month, 1);
            
        return view('siswa.attendance.month', compact('student', 'attendanceRecords', 'dateObj'));
    }
    
    /**
     * Display attendance by subject
     */
    public function bySubject(Subject $subject)
    {
        $student = auth()->user();
        
        // Get attendance records for specified subject
        $attendanceRecords = AttendanceRecord::whereHas('attendance', function($query) use ($subject) {
                $query->where('subject_id', $subject->id);
            })
            ->where('student_id', $student->id)
            ->with(['attendance.teacher'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('siswa.attendance.by-subject', compact('student', 'subject', 'attendanceRecords'));
    }
    
    /**
     * Get attendance summary for a given month
     */
    private function getAttendanceSummary($studentId, $month, $year)
    {
        // Get all attendance records for this month
        $records = AttendanceRecord::whereHas('attendance', function($query) use ($month, $year) {
                $query->whereMonth('date', $month)
                      ->whereYear('date', $year);
            })
            ->where('student_id', $studentId)
            ->get();
            
        $summary = [
            'total' => $records->count(),
            'present' => $records->where('status', 'present')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'late' => $records->where('status', 'late')->count(),
            'excused' => $records->where('status', 'excused')->count(),
        ];
        
        $summary['attendance_rate'] = $summary['total'] > 0 
            ? round((($summary['present'] + $summary['late']) / $summary['total']) * 100, 1) 
            : 0;
            
        return $summary;
    }
}
