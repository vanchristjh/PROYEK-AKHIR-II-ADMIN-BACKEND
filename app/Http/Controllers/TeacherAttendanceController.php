<?php

namespace App\Http\Controllers;

use App\Models\TeacherAttendance;
use App\Models\TeacherAttendanceRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TeacherAttendanceController extends Controller
{
    /**
     * Display a listing of the attendance sessions.
     */
    public function index(Request $request)
    {
        $query = TeacherAttendance::query()
            ->latest('date')
            ->latest('start_time');

        // Apply date filter
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Apply activity type filter
        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        $attendanceSessions = $query->paginate(10);
        
        // Get unique activity types for the filter dropdown
        $activityTypes = TeacherAttendance::distinct()
            ->pluck('activity_type')
            ->filter()
            ->values();

        return view('dashboard.teacher-attendance.index', compact('attendanceSessions', 'activityTypes'));
    }

    /**
     * Show the form for creating a new attendance session.
     */
    public function create()
    {
        return view('dashboard.teacher-attendance.create');
    }

    /**
     * Store a newly created attendance session in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'activity_type' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $attendance = TeacherAttendance::create([
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'activity_type' => $request->activity_type,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('teacher-attendance.edit', $attendance->id)
            ->with('success', 'Sesi absensi guru berhasil dibuat. Silakan isi kehadiran guru.');
    }

    /**
     * Display the specified attendance session.
     */
    public function show(TeacherAttendance $teacherAttendance)
    {
        $teacherAttendance->load(['creator', 'records.teacher']);
        
        $attendances = $teacherAttendance->records()->with('teacher')->get();
        
        $attendanceSummary = $teacherAttendance->getStatusSummary();
        
        $session = $teacherAttendance; // Alias for the view

        return view('dashboard.teacher-attendance.show', compact(
            'teacherAttendance', 
            'attendances', 
            'attendanceSummary', 
            'session'
        ));
    }

    /**
     * Show the form for editing the specified attendance session.
     */
    public function edit(TeacherAttendance $teacherAttendance)
    {
        // Redirect if attendance is already completed
        if ($teacherAttendance->is_completed) {
            return redirect()->route('teacher-attendance.show', $teacherAttendance->id)
                ->with('error', 'Absensi ini sudah ditandai selesai dan tidak dapat diubah.');
        }
        
        // Get all teachers
        $teachers = User::where('role', 'teacher')
            ->orderBy('name')
            ->get();
        
        // Get existing attendance records
        $existingRecords = $teacherAttendance->records()->with('teacher')->get();
        
        // Create a lookup array for easy access
        $attendances = [];
        foreach ($existingRecords as $record) {
            $attendances[$record->teacher_id] = $record;
        }

        $session = $teacherAttendance; // Alias for the view

        return view('dashboard.teacher-attendance.edit', compact(
            'teacherAttendance', 
            'teachers', 
            'attendances', 
            'session'
        ));
    }

    /**
     * Update the specified attendance session.
     */
    public function update(Request $request, TeacherAttendance $teacherAttendance)
    {
        // Validate basic input
        $request->validate([
            'teacher_id' => 'required|array',
            'teacher_id.*' => 'exists:users,id',
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
            $teacherIds = $request->teacher_id;
            foreach ($teacherIds as $teacherId) {
                $status = $request->status[$teacherId] ?? 'hadir';
                $notes = $request->notes[$teacherId] ?? null;
                
                // Check if there's a photo upload for this teacher
                $photoPath = null;
                if ($request->hasFile("photo.$teacherId")) {
                    $file = $request->file("photo.$teacherId");
                    $path = $file->store('teacher-attendance-photos', 'public');
                    $photoPath = $path;
                }
                
                // Get the existing record if any
                $record = TeacherAttendanceRecord::where('teacher_attendance_id', $teacherAttendance->id)
                    ->where('teacher_id', $teacherId)
                    ->first();
                
                $recordData = [
                    'status' => $status,
                    'notes' => $notes,
                    'check_in_time' => Carbon::now()->format('H:i:s'),
                ];
                
                // Only update photo if a new one was uploaded
                if ($photoPath) {
                    // Delete old photo if exists
                    if ($record && $record->photo) {
                        Storage::disk('public')->delete($record->photo);
                    }
                    $recordData['photo'] = $photoPath;
                }

                // Update or create the record
                TeacherAttendanceRecord::updateOrCreate(
                    ['teacher_attendance_id' => $teacherAttendance->id, 'teacher_id' => $teacherId],
                    $recordData
                );
            }

            // Update attendance session status
            $teacherAttendance->is_completed = $request->has('is_completed');
            $teacherAttendance->save();

            DB::commit();
            return redirect()->route('teacher-attendance.show', $teacherAttendance->id)
                ->with('success', 'Data absensi guru berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified attendance session.
     */
    public function destroy(TeacherAttendance $teacherAttendance)
    {
        try {
            DB::beginTransaction();
            
            // Delete all photos associated with this attendance session
            $records = $teacherAttendance->records()->whereNotNull('photo')->get();
            foreach ($records as $record) {
                Storage::disk('public')->delete($record->photo);
            }
            
            // Delete all related records first to avoid foreign key constraint issues
            $teacherAttendance->records()->delete();
            
            // Now delete the attendance session
            $teacherAttendance->delete();
            
            DB::commit();
            
            return redirect()->route('teacher-attendance.index')
                ->with('success', 'Sesi absensi guru berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display attendance reports.
     */
    public function report(Request $request)
    {
        // Build attendance query
        $query = TeacherAttendanceRecord::with(['session', 'teacher']);

        // Apply filters
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
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

        if ($request->filled('activity_type')) {
            $query->whereHas('session', function ($q) use ($request) {
                $q->where('activity_type', $request->activity_type);
            });
        }

        // Get all teachers for the filter
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        
        // Get unique activity types
        $activityTypes = TeacherAttendance::distinct()
            ->pluck('activity_type')
            ->filter()
            ->values();

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
            'total_sessions' => TeacherAttendance::count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
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

        return view('dashboard.teacher-attendance.report', compact(
            'attendances',
            'teachers',
            'activityTypes',
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
}
