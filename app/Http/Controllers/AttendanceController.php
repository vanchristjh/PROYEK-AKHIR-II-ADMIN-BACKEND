<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceRecord;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the attendance sessions.
     */
    public function index(Request $request)
    {
        $query = Attendance::with(['class', 'subject'])
            ->latest('date')
            ->latest('start_time');

        // Apply filters
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $attendanceSessions = $query->paginate(10);
        $classes = ClassRoom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('dashboard.attendance.index', compact('attendanceSessions', 'classes', 'subjects'));
    }

    /**
     * Show the form for creating a new attendance session.
     */
    public function create()
    {
        $classes = ClassRoom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('dashboard.attendance.create', compact('classes', 'subjects'));
    }

    /**
     * Store a newly created attendance session in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:class_rooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'notes' => 'nullable|string',
        ]);

        $attendance = Attendance::create([
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('attendance.edit', $attendance->id)
            ->with('success', 'Sesi absensi berhasil dibuat. Silakan isi kehadiran siswa.');
    }

    /**
     * Display the specified attendance session.
     */
    public function show(Attendance $attendance)
    {
        $attendance->load(['class', 'subject', 'creator', 'records.student']);
        
        $attendances = $attendance->records()->with('student')->get();
        
        $attendanceSummary = $attendance->getStatusSummary();

        return view('dashboard.attendance.show', compact('attendance', 'attendances', 'attendanceSummary', 'session'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        // Redirect if attendance is already completed
        if ($attendance->is_completed) {
            return redirect()->route('attendance.show', $attendance->id)
                ->with('error', 'Absensi ini sudah ditandai selesai dan tidak dapat diubah.');
        }

        $attendance->load(['class', 'subject']);
        
        // Get all students in this class
        $students = Student::where('class_id', $attendance->class_id)
            ->orderBy('name')
            ->get();
        
        // Get existing attendance records
        $existingRecords = $attendance->records()->with('student')->get();
        
        // Create a lookup array for easy access
        $attendances = [];
        foreach ($existingRecords as $record) {
            $attendances[$record->student_id] = $record;
        }

        $session = $attendance; // Alias for the attendance

        return view('dashboard.attendance.edit', compact('attendance', 'students', 'attendances', 'session'));
    }

    /**
     * Update the specified attendance session.
     */
    public function update(Request $request, Attendance $attendance)
    {
        // Validate input
        $request->validate([
            'student_id' => 'required|array',
            'student_id.*' => 'exists:students,id',
            'status' => 'required|array',
            'status.*' => 'in:hadir,izin,sakit,alpa,terlambat',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string',
            'is_completed' => 'nullable|boolean',
        ]);

        // Start transaction
        DB::beginTransaction();
        try {
            // Update attendance records
            $studentIds = $request->student_id;
            foreach ($studentIds as $studentId) {
                $status = $request->status[$studentId] ?? 'hadir';
                $notes = $request->notes[$studentId] ?? null;

                // Update or create attendance record
                AttendanceRecord::updateOrCreate(
                    ['attendance_id' => $attendance->id, 'student_id' => $studentId],
                    ['status' => $status, 'notes' => $notes]
                );
            }

            // Update attendance session status
            $attendance->is_completed = $request->has('is_completed');
            $attendance->save();

            DB::commit();
            return redirect()->route('attendance.show', $attendance->id)
                ->with('success', 'Data absensi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified attendance session.
     */
    public function destroy(Attendance $attendance)
    {
        try {
            $attendance->delete();
            return redirect()->route('attendance.index')
                ->with('success', 'Sesi absensi berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display attendance reports.
     */
    public function report(Request $request)
    {
        // Get classes and students for filter
        $classes = ClassRoom::orderBy('name')->get();
        $students = collect();

        // If class is selected, get students from that class
        if ($request->filled('class_id')) {
            $students = Student::where('class_id', $request->class_id)
                ->orderBy('name')
                ->get();
        }

        // Build attendance query
        $query = AttendanceRecord::with(['session.class', 'session.subject', 'student']);

        // Apply filters
        if ($request->filled('class_id')) {
            $query->whereHas('session', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('month')) {
            $query->whereHas('session', function ($q) use ($request) {
                $q->whereMonth('date', $request->month);
            });
        }

        if ($request->filled('year')) {
            $query->whereHas('session', function ($q) use ($request) {
                $q->whereYear('date', $request->year);
            });
        }

        // Handle export request
        if ($request->has('export')) {
            return $this->exportReport($query, $request);
        }

        // Get paginated results for display
        $attendances = $query->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        // Calculate summary statistics
        $totalRecords = $query->count();
        $presentCount = clone $query;
        $presentCount = $presentCount->where('status', 'hadir')->count();
        $absentCount = $totalRecords - $presentCount;

        $summary = [
            'total_sessions' => Attendance::count(),
            'total_students' => Student::count(),
            'attendance_percentage' => $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100) . '%' : '0%',
            'absent_count' => $absentCount,
        ];

        // Calculate percentages by status
        $statuses = ['hadir', 'sakit', 'izin', 'alpa', 'terlambat'];
        $percentages = [];

        foreach ($statuses as $status) {
            $statusCount = clone $query;
            $count = $statusCount->where('status', $status)->count();
            $percentages[$status] = $totalRecords > 0 ? round(($count / $totalRecords) * 100) . '%' : '0%';
        }

        // Prepare chart data
        $chartData = $this->prepareChartData($query, $request);

        return view('dashboard.attendance.report', compact(
            'attendances',
            'classes',
            'students',
            'summary',
            'percentages',
            'chartData'
        ));
    }

    /**
     * Prepare data for attendance charts.
     */
    private function prepareChartData($query, $request)
    {
        // Get the date range based on request filters
        $year = $request->year ?? date('Y');
        $month = $request->month;

        // Start with an empty dataset
        $chartData = [
            'labels' => [],
            'hadir' => [],
            'sakit' => [],
            'izin' => [],
            'alpa' => [],
            'terlambat' => [],
        ];

        // If month is specified, show daily data for that month
        if ($month) {
            $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::createFromDate($year, $month, $day);
                $chartData['labels'][] = $date->format('d M');
                
                // Count status for this day
                foreach (['hadir', 'sakit', 'izin', 'alpa', 'terlambat'] as $status) {
                    $statusQuery = clone $query;
                    $count = $statusQuery->where('status', $status)
                        ->whereHas('session', function ($q) use ($date) {
                            $q->whereDate('date', $date);
                        })
                        ->count();
                    $chartData[$status][] = $count;
                }
            }
        } else {
            // Show monthly data for the selected year
            for ($month = 1; $month <= 12; $month++) {
                $chartData['labels'][] = Carbon::createFromDate($year, $month, 1)->format('M');
                
                // Count status for this month
                foreach (['hadir', 'sakit', 'izin', 'alpa', 'terlambat'] as $status) {
                    $statusQuery = clone $query;
                    $count = $statusQuery->where('status', $status)
                        ->whereHas('session', function ($q) use ($year, $month) {
                            $q->whereYear('date', $year)
                              ->whereMonth('date', $month);
                        })
                        ->count();
                    $chartData[$status][] = $count;
                }
            }
        }

        return $chartData;
    }

    /**
     * Export attendance data for a specific session.
     */
    public function export(Attendance $attendance)
    {
        // Here you would implement the export functionality
        // This is a placeholder that will be implemented later
        return redirect()->back()->with('success', 'Fitur ekspor akan segera tersedia.');
    }

    /**
     * Export attendance report data based on filters.
     */
    private function exportReport($query, $request)
    {
        // Here you would implement the export functionality
        // This is a placeholder that will be implemented later
        return redirect()->back()->with('success', 'Fitur ekspor akan segera tersedia.');
    }
}
