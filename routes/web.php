<?php

use Illuminate\Support\Facades\Route;

// Import controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminSubjectController;
use App\Http\Controllers\Admin\AdminClassroomController;
use App\Http\Controllers\Admin\AdminAnnouncementController;

// Update these imports to match your actual controller names
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\MaterialController as GuruMaterialController;
use App\Http\Controllers\Guru\AssignmentController as GuruAssignmentController;
use App\Http\Controllers\Guru\GradeController as GuruGradeController;
use App\Http\Controllers\Guru\AttendanceController as GuruAttendanceController;
use App\Http\Controllers\Guru\AnnouncementController as GuruAnnouncementController;

use App\Http\Controllers\Siswa\SiswaDashboardController;
use App\Http\Controllers\Siswa\SiswaScheduleController;
use App\Http\Controllers\Siswa\SiswaAssignmentController;
use App\Http\Controllers\Siswa\SiswaMaterialController;
use App\Http\Controllers\Siswa\SiswaGradeController;
use App\Http\Controllers\Siswa\SiswaAnnouncementController;
use App\Http\Controllers\Siswa\SubmissionController;
use App\Http\Controllers\Siswa\AttendanceController as SiswaAttendanceController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [App\Http\Controllers\Admin\AdminDashboardController::class, 'getStats'])->name('dashboard.stats');
    
    // User management
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    
    // Classroom management
    Route::resource('classrooms', App\Http\Controllers\Admin\ClassroomController::class);
    
    // Subject management
    Route::resource('subjects', App\Http\Controllers\Admin\SubjectController::class);
    
    // Announcement management
    Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class);
    Route::get('announcements/{announcement}/download', [App\Http\Controllers\Admin\AdminAnnouncementController::class, 'download'])
        ->name('announcements.download');
});

// Guru Routes
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [App\Http\Controllers\Guru\GuruDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/refresh', [App\Http\Controllers\Guru\GuruDashboardController::class, 'refresh'])->name('dashboard.refresh');
    
    // Schedule routes for teachers
    Route::get('/schedule', [App\Http\Controllers\Guru\ScheduleController::class, 'index'])->name('schedule.index');
    
    // Materials management
    Route::resource('materials', App\Http\Controllers\Guru\MaterialController::class);
    
    // Assignments management
    Route::resource('assignments', App\Http\Controllers\Guru\AssignmentController::class);
    
    // Submissions management for assignments
    Route::get('assignments/{assignment}/submissions', [App\Http\Controllers\Guru\SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('assignments/{assignment}/submissions/{submission}', [App\Http\Controllers\Guru\SubmissionController::class, 'show'])->name('submissions.show');
    Route::put('assignments/{assignment}/submissions/{submission}', [App\Http\Controllers\Guru\SubmissionController::class, 'update'])->name('submissions.update');
    
    // Attendance management
    Route::resource('attendance', App\Http\Controllers\Guru\AttendanceController::class);
    
    // AJAX routes for dependent dropdowns
    Route::get('subjects/{subject}/classrooms', function (App\Models\Subject $subject) {
        $teacher = auth()->user();
        
        // Get classrooms where this subject is taught
        $classrooms = $subject->classrooms;
        
        // Format data for frontend
        return response()->json($classrooms->map(function($classroom) {
            return [
                'id' => $classroom->id,
                'name' => $classroom->name,
            ];
        }));
    })->name('guru.subjects.classrooms');
    
    // Grades management
    Route::resource('grades', App\Http\Controllers\Guru\GradeController::class);
    
    // Announcements management
    Route::resource('announcements', App\Http\Controllers\Guru\AnnouncementController::class);
    Route::get('announcements/{announcement}/download', [App\Http\Controllers\Guru\AnnouncementController::class, 'download'])
        ->name('announcements.download');
    
    // AJAX routes for dependent dropdowns
    Route::get('subjects/{subject}/classrooms', function (App\Models\Subject $subject) {
        $teacher = auth()->user();
        
        // Check if teacher has this subject
        $teachesSubject = $teacher->teacherSubjects->contains($subject->id);
        
        if ($teachesSubject) {
            // Return all classrooms that have this subject
            $classrooms = $subject->classrooms;
        } else {
            // If they don't teach the subject, show no classrooms
            $classrooms = collect();
        }
        
        return response()->json($classrooms);
    });
    
    Route::get('classrooms/{classroom}/students', function (App\Models\Classroom $classroom) {
        // Return students in this classroom
        $students = $classroom->students()->get();
        return response()->json($students);
    });
});

// Siswa Routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [App\Http\Controllers\Siswa\SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/refresh', [App\Http\Controllers\Siswa\SiswaDashboardController::class, 'refresh'])->name('dashboard.refresh');
    
    // Assignments for students
    Route::resource('assignments', App\Http\Controllers\Siswa\AssignmentController::class)->only(['index', 'show']);
    
    // Submissions for students
    Route::resource('submissions', App\Http\Controllers\Siswa\SubmissionController::class)->only(['index', 'show', 'store', 'update']);
    
    // Materials for students
    Route::resource('materials', App\Http\Controllers\Siswa\MaterialController::class)->only(['index', 'show']);
    
    // Schedule for students
    Route::get('/schedule', [App\Http\Controllers\Siswa\ScheduleController::class, 'index'])->name('schedule.index');
    
    // Grades for students
    Route::get('/grades', [App\Http\Controllers\Siswa\GradeController::class, 'index'])->name('grades.index');
    
    // Announcements for students
    Route::get('/announcements', [App\Http\Controllers\Siswa\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/{announcement}', [App\Http\Controllers\Siswa\AnnouncementController::class, 'show'])->name('announcements.show');
    Route::get('/announcements/{announcement}/download', [App\Http\Controllers\Siswa\AnnouncementController::class, 'download'])
        ->name('announcements.download');
    
    // Student Attendance Routes (read-only)
    Route::get('attendance', [App\Http\Controllers\Siswa\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/{month?}/{year?}', [App\Http\Controllers\Siswa\AttendanceController::class, 'month'])->name('attendance.month');
    Route::get('attendance/subject/{subject}', [App\Http\Controllers\Siswa\AttendanceController::class, 'bySubject'])->name('attendance.by-subject');
});

// Error routes
Route::fallback(function () {
    return view('errors.404');
});
