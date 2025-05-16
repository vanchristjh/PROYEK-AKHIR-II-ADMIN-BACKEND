<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use PDF;

class ScheduleController extends Controller
{
    /**
     * Display a listing of teacher's schedules.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get current teacher
        $teacher = Teacher::where('user_id', Auth::id())->first();
        if (!$teacher) {
            return view('guru.schedule.index', [
                'schedules' => collect([]),
                'schedulesByDay' => []
            ])->withErrors('Data guru tidak ditemukan.');
        }
        
        // Get all schedules for this teacher
        $schedules = Schedule::with(['subject', 'classroom'])
                        ->where('teacher_id', $teacher->id)
                        ->orderBy('day')
                        ->orderBy('start_time')
                        ->get();
                        
        // Group schedules by day for display
        $schedulesByDay = $schedules->groupBy('day');
        
        // Count new schedules (created within the last 3 days)
        $newSchedulesCount = $schedules->filter(function($schedule) {
            return $schedule->created_at >= now()->subDays(3);
        })->count();
        
        if ($newSchedulesCount > 0) {
            session()->flash('new_schedules_count', $newSchedulesCount);
        }

        return view('guru.schedule.index', compact('schedules', 'schedulesByDay'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get current teacher
        $teacher = Teacher::where('user_id', Auth::id())->first();
        if (!$teacher) {
            return redirect()->route('guru.schedule.index')
                ->with('error', 'Data guru tidak ditemukan.');
        }
        
        // Get subjects this teacher can teach
        $subjects = Subject::whereHas('teachers', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->get();
        
        if ($subjects->isEmpty()) {
            $subjects = Subject::all(); // Fallback to all subjects
        }
        
        // Get all classrooms
        $classrooms = Classroom::orderBy('name')->get();
        
        return view('guru.schedule.create', compact('subjects', 'classrooms'));
    }

    /**
     * Store a newly created schedule in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate form data
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'day' => 'required|integer|between:1,7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:50',
        ], [
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Get current teacher
        $teacher = Teacher::where('user_id', Auth::id())->first();
        if (!$teacher) {
            return redirect()->route('guru.schedule.index')
                ->with('error', 'Data guru tidak ditemukan.');
        }
        
        // Check for schedule conflicts
        $conflicts = $this->checkConflicts(
            $request->day,
            $request->start_time,
            $request->end_time,
            $teacher->id,
            $request->classroom_id
        );
        
        if ($conflicts->isNotEmpty()) {
            return redirect()->back()
                ->with('error', 'Terdapat konflik jadwal. Anda sudah mengajar di waktu yang sama atau kelas sudah memiliki jadwal pada waktu tersebut.')
                ->withInput();
        }
        
        // Create new schedule
        $schedule = new Schedule();
        $schedule->subject_id = $request->subject_id;
        $schedule->classroom_id = $request->classroom_id;
        $schedule->teacher_id = $teacher->id;        
        $schedule->day = $request->day;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->room = $request->room;
        $schedule->created_by = Auth::id();
        $schedule->save();
        
        return redirect()->route('guru.schedule.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified schedule.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Get current teacher
        $teacher = Teacher::where('user_id', Auth::id())->first();
        if (!$teacher) {
            return redirect()->route('guru.schedule.index')
                ->with('error', 'Data guru tidak ditemukan.');
        }
        
        // Get schedule and check ownership
        $schedule = Schedule::findOrFail($id);
        if ($schedule->teacher_id != $teacher->id) {
            return redirect()->route('guru.schedule.index')
                ->with('error', 'Anda tidak memiliki izin untuk mengedit jadwal ini.');
        }
        
        // Get subjects this teacher can teach
        $subjects = Subject::whereHas('teachers', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->get();
        
        if ($subjects->isEmpty()) {
            $subjects = Subject::all(); // Fallback to all subjects
        }
        
        // Get all classrooms
        $classrooms = Classroom::orderBy('name')->get();
        
        return view('guru.schedule.edit', compact('schedule', 'subjects', 'classrooms'));
    }

    /**
     * Update the specified schedule in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate form data
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'day' => 'required|integer|between:1,7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:50',
        ], [
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Get current teacher
        $teacher = Teacher::where('user_id', Auth::id())->first();
        if (!$teacher) {
            return redirect()->route('guru.schedule.index')
                ->with('error', 'Data guru tidak ditemukan.');
        }
        
        // Get schedule and check ownership
        $schedule = Schedule::findOrFail($id);
        if ($schedule->teacher_id != $teacher->id) {
            return redirect()->route('guru.schedule.index')
                ->with('error', 'Anda tidak memiliki izin untuk mengedit jadwal ini.');
        }
        
        // Check for schedule conflicts (excluding this schedule)
        $conflicts = $this->checkConflicts(
            $request->day,
            $request->start_time,
            $request->end_time,
            $teacher->id,
            $request->classroom_id,
            $id
        );
        
        if ($conflicts->isNotEmpty()) {
            return redirect()->back()
                ->with('error', 'Terdapat konflik jadwal. Anda sudah mengajar di waktu yang sama atau kelas sudah memiliki jadwal pada waktu tersebut.')
                ->withInput();
        }
        
        // Update schedule
        $schedule->subject_id = $request->subject_id;
        $schedule->classroom_id = $request->classroom_id;
        $schedule->day = $request->day;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->room = $request->room;
        $schedule->updated_at = now();
        $schedule->save();
        
        return redirect()->route('guru.schedule.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Remove the specified schedule from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get current teacher
        $teacher = Teacher::where('user_id', Auth::id())->first();
        if (!$teacher) {
            return redirect()->route('guru.schedule.index')
                ->with('error', 'Data guru tidak ditemukan.');
        }
        
        // Get schedule and check ownership
        $schedule = Schedule::findOrFail($id);
        if ($schedule->teacher_id != $teacher->id) {
            return redirect()->route('guru.schedule.index')
                ->with('error', 'Anda tidak memiliki izin untuk menghapus jadwal ini.');
        }
        
        $schedule->delete();
        
        return redirect()->route('guru.schedule.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Export schedules to PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportPDF()
    {
        // Get current teacher
        $teacher = Teacher::where('user_id', Auth::id())->first();
        if (!$teacher) {
            return redirect()->route('guru.schedule.index')
                ->with('error', 'Data guru tidak ditemukan.');
        }
        
        $schedules = Schedule::with(['subject', 'classroom'])
            ->where('teacher_id', $teacher->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $schedulesByDay = $schedules->groupBy('day');
        
        $pdf = PDF::loadView('guru.schedule.pdf', [
            'teacher' => Auth::user(),
            'schedulesByDay' => $schedulesByDay
        ]);
        
        return $pdf->download('jadwal-mengajar-' . Auth::user()->name . '.pdf');
    }

    /**
     * Export schedules to Excel.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportExcel() 
    {
        // Get current teacher
        $teacher = Teacher::where('user_id', Auth::id())->first();
        if (!$teacher) {
            return redirect()->route('guru.schedule.index')
                ->with('error', 'Data guru tidak ditemukan.');
        }
        
        $schedules = Schedule::with(['subject', 'classroom'])
            ->where('teacher_id', $teacher->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();
        
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="jadwal-mengajar-'.Auth::user()->name.'.xlsx"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        // Convert day numbers to names
        $dayNames = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];
        
        // Create CSV content (which Excel can open)
        $callback = function() use ($schedules, $dayNames) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // Headers
            fputcsv($file, ['Hari', 'Mata Pelajaran', 'Kelas', 'Waktu Mulai', 'Waktu Selesai', 'Ruangan']);
            
            // Data rows
            foreach ($schedules as $schedule) {
                $day = $dayNames[$schedule->day] ?? $schedule->day;
                
                $row = [
                    $day,
                    $schedule->subject->name ?? 'N/A',
                    $schedule->classroom->name ?? 'N/A',
                    $schedule->start_time,
                    $schedule->end_time,
                    $schedule->room ?? '-'
                ];
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export schedules to CSV.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportCSV() 
    {
        // Get current teacher
        $teacher = Teacher::where('user_id', Auth::id())->first();
        if (!$teacher) {
            return redirect()->route('guru.schedule.index')
                ->with('error', 'Data guru tidak ditemukan.');
        }
        
        $schedules = Schedule::with(['subject', 'classroom'])
            ->where('teacher_id', $teacher->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="jadwal-mengajar-'.Auth::user()->name.'.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($schedules) {
            $file = fopen('php://output', 'w');
            
            // Add BOM to fix UTF-8 in Excel
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // Add headers
            fputcsv($file, ['Hari', 'Mata Pelajaran', 'Kelas', 'Waktu Mulai', 'Waktu Selesai', 'Ruangan']);
            
            // Add data rows
            foreach ($schedules as $schedule) {
                // Convert day number to name if needed
                $day = $schedule->day;
                if (is_numeric($day)) {
                    $dayNames = [
                        1 => 'Senin',
                        2 => 'Selasa',
                        3 => 'Rabu',
                        4 => 'Kamis',
                        5 => 'Jumat',
                        6 => 'Sabtu',
                        7 => 'Minggu'
                    ];
                    $day = $dayNames[$day] ?? $day;
                }
                
                $row = [
                    $day,
                    $schedule->subject->name ?? 'N/A',
                    $schedule->classroom->name ?? 'N/A',
                    $schedule->start_time,
                    $schedule->end_time,
                    $schedule->room ?? '-'
                ];
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    /**
     * Check for schedule conflicts.
     *
     * @param  int  $day
     * @param  string  $startTime
     * @param  string  $endTime
     * @param  int  $teacherId
     * @param  int  $classroomId
     * @param  int|null  $excludeId
     * @return \Illuminate\Support\Collection
     */
    private function checkConflicts($day, $startTime, $endTime, $teacherId, $classroomId, $excludeId = null)
    {
        $query = Schedule::where('day', $day)
            ->where(function($q) use ($startTime, $endTime) {
                // Check for overlapping time slots
                $q->where(function($q1) use ($startTime, $endTime) {
                    $q1->where('start_time', '<=', $startTime)
                        ->where('end_time', '>', $startTime);
                })->orWhere(function($q2) use ($startTime, $endTime) {
                    $q2->where('start_time', '<', $endTime)
                        ->where('end_time', '>=', $endTime);
                })->orWhere(function($q3) use ($startTime, $endTime) {
                    $q3->where('start_time', '>=', $startTime)
                        ->where('end_time', '<=', $endTime);
                });
            })
            ->where(function($q) use ($teacherId, $classroomId) {
                // Either same teacher or same classroom
                $q->where('teacher_id', $teacherId)
                  ->orWhere('classroom_id', $classroomId);
            });
        
        // Exclude current schedule if editing
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->get();
    }
}