<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ImagesHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Added import for Str class
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\StudentsExport;
use App\Services\NotificationService;

class StudentController extends Controller
{
    protected $notificationService;
    
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $query = User::where('role', 'student');
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('nis', 'like', "%{$searchTerm}%")
                  ->orWhere('nisn', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }
        
        // Filter by class
        if ($request->has('class_id') && !empty($request->class_id)) {
            $query->where('class_id', $request->class_id);
        }
        
        // Filter by academic year
        if ($request->has('academic_year') && !empty($request->academic_year)) {
            $query->where('academic_year', $request->academic_year);
        }
        
        $students = $query->paginate(10);
        
        // Get classes for filter dropdown
        $classes = ClassRoom::orderBy('level')->orderBy('name')->get();
        
        // Get academic years for filter dropdown
        $academicYears = User::where('role', 'student')
            ->whereNotNull('academic_year')
            ->select('academic_year')
            ->distinct()
            ->pluck('academic_year');
        
        return view('dashboard.students.index', compact('students', 'classes', 'academicYears'));
    }

    public function create()
    {
        try {
            // First try to get classes from the class_rooms table
            if (Schema::hasTable('class_rooms')) {
                $classes = ClassRoom::orderBy('level')->orderBy('name')->get();
            } else if (Schema::hasTable('classes')) {
                // If class_rooms doesn't exist, try the 'classes' table as fallback
                $classes = DB::table('classes')->orderBy('level')->orderBy('name')->get();
            } else {
                $classes = collect([]);
            }
        } catch (QueryException $e) {
            // If there's a database error, provide an empty collection
            $classes = collect([]);
        }
        
        return view('dashboard.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nis' => 'required|string|unique:users',
            'nisn' => 'nullable|string|unique:users',
            'class_id' => 'required|exists:class_rooms,id',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:L,P',
            'academic_year' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:15',
        ]);

        try {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'student',
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'class_id' => $request->class_id,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'academic_year' => $request->academic_year,
                'parent_name' => $request->parent_name,
                'parent_phone' => $request->parent_phone,
            ];

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                try {
                    // Store the photo
                    $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
                    $userData['profile_photo'] = $photoPath;
                } catch (\Exception $e) {
                    // Log the error but continue creating the user
                    \Log::error('Error uploading profile photo: ' . $e->getMessage());
                }
            }
            
            $student = User::create($userData);
            
            // Create a notification for admins
            $this->notificationService->notifyAdmins(
                'Siswa Baru',
                'Siswa baru telah ditambahkan: ' . $student->name,
                'bx-user-plus',
                'bg-success-light text-success',
                route('students.show', $student->id),
                false
            );
            
            return redirect()->route('students.index')
                ->with('success', 'Akun siswa berhasil dibuat.');
                   
        } catch (QueryException $e) {
            return back()->withInput()->withErrors([
                'error' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    public function show(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }
        
        // Get attendance statistics for current month
        $attendanceStats = $this->getAttendanceStatistics($student->id);
        
        return view('dashboard.students.show', compact('student', 'attendanceStats'));
    }

    public function edit(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }
        
        try {
            // First try to get classes from the class_rooms table
            if (Schema::hasTable('class_rooms')) {
                $classes = ClassRoom::orderBy('level')->orderBy('name')->get();
            } else if (Schema::hasTable('classes')) {
                // If class_rooms doesn't exist, try the 'classes' table as fallback
                $classes = DB::table('classes')->orderBy('level')->orderBy('name')->get();
            } else {
                $classes = collect([]);
            }
        } catch (QueryException $e) {
            // If there's a database error, provide an empty collection
            $classes = collect([]);
        }
        
        return view('dashboard.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$student->id,
            'nis' => 'required|string|unique:users,nis,'.$student->id,
            'nisn' => 'nullable|string|unique:users,nisn,'.$student->id,
            'class_id' => 'required|exists:class_rooms,id',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'academic_year' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:15',
        ]);

        try {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'class_id' => $request->class_id,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'academic_year' => $request->academic_year,
                'parent_name' => $request->parent_name,
                'parent_phone' => $request->parent_phone,
            ];

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($student->profile_photo) {
                    Storage::disk('public')->delete($student->profile_photo);
                }
                
                // Store the new photo
                $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
                $userData['profile_photo'] = $photoPath;
            }
            
            $student->update($userData);
            
            // Update password if provided
            if ($request->filled('password')) {
                $request->validate([
                    'password' => 'required|string|min:8|confirmed',
                ]);
                
                $student->update([
                    'password' => Hash::make($request->password),
                ]);
            }
            
            // Create a notification
            $this->notificationService->notifyAdmins(
                'Data Siswa Diperbarui',
                'Data siswa ' . $student->name . ' telah diperbarui',
                'bx-user-check',
                'bg-info-light text-info',
                route('students.show', $student->id),
                false
            );
            
            return redirect()->route('students.index')
                ->with('success', 'Akun siswa berhasil diperbarui.');
                
        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    public function destroy(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }
        
        try {
            // Delete profile photo if exists
            if ($student->profile_photo) {
                Storage::disk('public')->delete($student->profile_photo);
            }
            
            $student->delete();
            
            // Create a notification
            $this->notificationService->notifyAdmins(
                'Siswa Dihapus',
                'Siswa ' . $student->name . ' telah dihapus dari sistem',
                'bx-user-x',
                'bg-danger-light text-danger',
                null,
                true
            );
            
            return redirect()->route('students.index')
                ->with('success', 'Akun siswa berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get attendance statistics for a student.
     *
     * @param int $studentId
     * @return array
     */
    private function getAttendanceStatistics($studentId)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        // Check if AttendanceRecord model exists
        if (!class_exists('App\Models\AttendanceRecord')) {
            // Return default values if model doesn't exist
            return [
                'hadir' => 0,
                'izin' => 0,
                'sakit' => 0,
                'alpa' => 0,
                'terlambat' => 0,
                'hadir_percent' => 0,
                'izin_percent' => 0,
                'sakit_percent' => 0,
                'alpa_percent' => 0,
            ];
        }
        
        try {
            // Count attendance by status
            $records = DB::table('attendance_records')
                ->join('attendances', 'attendance_records.attendance_id', '=', 'attendances.id')
                ->where('attendance_records.student_id', $studentId)
                ->whereBetween('attendances.date', [$startOfMonth, $endOfMonth])
                ->select('attendance_records.status', DB::raw('count(*) as total'))
                ->groupBy('attendance_records.status')
                ->get();
            
            // Initialize counters
            $stats = [
                'hadir' => 0,
                'izin' => 0,
                'sakit' => 0,
                'alpa' => 0,
                'terlambat' => 0,
            ];
            
            // Populate counters
            foreach ($records as $record) {
                if (isset($stats[$record->status])) {
                    $stats[$record->status] = $record->total;
                }
            }
            
            // Calculate total
            $total = array_sum($stats);
            if ($total > 0) {
                // Calculate percentages
                $stats['hadir_percent'] = round(($stats['hadir'] / $total) * 100);
                $stats['izin_percent'] = round(($stats['izin'] / $total) * 100);
                $stats['sakit_percent'] = round(($stats['sakit'] / $total) * 100);
                $stats['alpa_percent'] = round(($stats['alpa'] / $total) * 100);
            } else {
                $stats['hadir_percent'] = 0;
                $stats['izin_percent'] = 0;
                $stats['sakit_percent'] = 0;
                $stats['alpa_percent'] = 0;
            }
            
            return $stats;
            
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error fetching attendance statistics: ' . $e->getMessage());
            
            // Return default values
            return [
                'hadir' => 0,
                'izin' => 0,
                'sakit' => 0,
                'alpa' => 0,
                'terlambat' => 0,
                'hadir_percent' => 0,
                'izin_percent' => 0,
                'sakit_percent' => 0,
                'alpa_percent' => 0,
            ];
        }
    }
    
    /**
     * Export students data to Excel/CSV.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
        try {
            // Get student data
            $students = User::where('role', 'student')
                ->get();
                
            $filename = 'data_siswa_' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];
            
            $handle = fopen('php://output', 'w');
            
            // Add UTF-8 BOM to fix special characters in Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add header row
            fputcsv($handle, [
                'NIS',
                'NISN',
                'Nama',
                'Kelas',
                'Jenis Kelamin',
                'Email',
                'Tahun Akademik',
                'Orang Tua/Wali',
            ]);
            
            // Add data rows
            foreach ($students as $student) {
                $class = $student->class ? $student->class->name : '-';
                $gender = ($student->gender == 'male' || $student->gender == 'L') ? 'Laki-laki' : 'Perempuan';
                
                fputcsv($handle, [
                    $student->nis ?? '-',
                    $student->nisn ?? '-',
                    $student->name,
                    $class,
                    $gender,
                    $student->email,
                    $student->academic_year ?? '-',
                    $student->parent_name ?? '-',
                ]);
            }
            
            fclose($handle);
            
            return response()->make('', 200, $headers);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    /**
     * Export students data to PDF.
     * 
     * @return \Illuminate\Http\Response
     */
    public function exportPdf()
    {
        try {
            // Get student data without eager loading the class relationship
            $students = User::where('role', 'student')
                ->get();
                
            // Generate PDF using DomPDF - fix the argument ordering
            $pdf = \PDF::loadView('exports.students-pdf', [
                'students' => $students,
                'date' => now()->format('d F Y')
            ]);
            
            // Set options after creating the PDF instance
            $pdf->setOptions([
                'defaultFont' => 'dejavu serif',
                'isRemoteEnabled' => true
            ]);
            
            // Set paper size to landscape A4
            $pdf->setPaper('a4', 'landscape');
            
            // Download the PDF file
            return $pdf->download('data_siswa_'.date('Y-m-d').'.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error exporting PDF: ' . $e->getMessage());
        }
    }
}