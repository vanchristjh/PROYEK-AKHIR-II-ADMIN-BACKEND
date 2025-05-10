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

// Controllers for Profile and Settings
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Guru\ProfileController as GuruProfileController;
use App\Http\Controllers\Guru\SettingsController as GuruSettingsController;

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
    // If user is logged in, they'll be redirected by the guest middleware
    return redirect()->route('login');
});

// Authentication Routes - use only one controller for consistency
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');

// Clear route cache if there's a redirect loop (helpful for debugging)
Route::get('/clear-cache', function() {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return redirect('/')->with('message', 'Cache cleared successfully');
});

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
    
    // Schedule management
    Route::resource('schedule', App\Http\Controllers\Admin\AdminScheduleController::class);
    Route::get('/schedule/classroom/{classroom_id}', [App\Http\Controllers\Admin\AdminScheduleController::class, 'getSchedulesByClassroom'])->name('schedule.by-classroom');
    Route::get('/schedule/bulk-create', [App\Http\Controllers\Admin\AdminScheduleController::class, 'bulkCreate'])->name('schedule.bulk-create');
    Route::post('/schedule/bulk-store', [App\Http\Controllers\Admin\AdminScheduleController::class, 'bulkStore'])->name('schedule.bulk-store');
    Route::get('/schedule/calendar', [App\Http\Controllers\Admin\AdminScheduleController::class, 'calendar'])->name('schedule.calendar');
    Route::get('/schedule/calendar-data', [App\Http\Controllers\Admin\AdminScheduleController::class, 'getCalendarData'])->name('schedule.calendar-data');
    Route::get('/schedule/export-classroom/{classroom_id}', [App\Http\Controllers\Admin\AdminScheduleController::class, 'exportClassroomSchedule'])->name('schedule.export-classroom');
    
    // Announcement management
    Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class);
    Route::get('announcements/{announcement}/download', [App\Http\Controllers\Admin\AdminAnnouncementController::class, 'download'])
        ->name('announcements.download');

    // Profile routes
    Route::get('profile', [AdminProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');

    // Settings routes
    Route::get('settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [AdminSettingsController::class, 'update'])->name('settings.update');
});

// Guru Routes
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [App\Http\Controllers\Guru\GuruDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/refresh', [App\Http\Controllers\Guru\GuruDashboardController::class, 'refresh'])->name('dashboard.refresh');
    
    // Schedule routes for teachers with full CRUD
    Route::resource('schedule', App\Http\Controllers\Guru\ScheduleController::class);
    
    // Materials management
    Route::resource('materials', App\Http\Controllers\Guru\MaterialController::class);
    
    // Assignments management
    Route::resource('assignments', App\Http\Controllers\Guru\AssignmentController::class);
    
    // Submissions management for assignments
    Route::get('assignments/{assignment}/submissions', [App\Http\Controllers\Guru\SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('assignments/{assignment}/submissions/{submission}', [App\Http\Controllers\Guru\SubmissionController::class, 'show'])->name('submissions.show');
    Route::put('assignments/{assignment}/submissions/{submission}', [App\Http\Controllers\Guru\SubmissionController::class, 'update'])->name('submissions.update');
    Route::get('assignments/{assignment}/submissions/{submission}/download', [App\Http\Controllers\Guru\SubmissionController::class, 'download'])->name('submissions.download');
    Route::post('assignments/{assignment}/submissions/batch-grade', [App\Http\Controllers\Guru\SubmissionController::class, 'batchGrade'])->name('submissions.batch-grade');
    
    // Teacher Submission Management Routes
    Route::get('/assignments/{assignment}/submissions', [App\Http\Controllers\Guru\SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/assignments/{assignment}/submissions/{submission}', [App\Http\Controllers\Guru\SubmissionController::class, 'show'])->name('submissions.show');
    Route::put('/assignments/{assignment}/submissions/{submission}/grade', [App\Http\Controllers\Guru\SubmissionController::class, 'grade'])->name('submissions.grade');
    Route::get('/assignments/{assignment}/submissions/{submission}/download', [App\Http\Controllers\Guru\SubmissionController::class, 'download'])->name('submissions.download');
    Route::get('/assignments/{assignment}/submissions/{submission}/preview', [App\Http\Controllers\Guru\SubmissionController::class, 'preview'])->name('submissions.preview');
    Route::post('/assignments/{assignment}/submissions/mass-grade', [App\Http\Controllers\Guru\SubmissionController::class, 'massGrade'])->name('submissions.mass-grade');
    Route::post('/assignments/{assignment}/submissions/zero/{student}', [App\Http\Controllers\Guru\SubmissionController::class, 'markZero'])->name('submissions.zero');
    Route::get('/assignments/{assignment}/submissions/export', [App\Http\Controllers\Guru\SubmissionController::class, 'export'])->name('submissions.export');
    
    // Attendance management
    Route::resource('attendance', App\Http\Controllers\Guru\AttendanceController::class);
    
    // AJAX routes for dependent dropdowns redirected to the controller
    
    // Grades management
    Route::resource('grades', App\Http\Controllers\Guru\GradeController::class);
    
    // AJAX routes for dependent dropdowns
    Route::get('subjects/{subject}/classrooms', [App\Http\Controllers\Guru\GradeController::class, 'getClassroomsBySubject'])
        ->name('grades.getClassrooms');
    Route::get('classrooms/{classroom}/students', [App\Http\Controllers\Guru\GradeController::class, 'getStudentsByClassroom'])
        ->name('grades.getStudents');
    
    // Announcements management
    Route::resource('announcements', App\Http\Controllers\Guru\AnnouncementController::class);
    Route::get('announcements/{announcement}/download', [App\Http\Controllers\Guru\AnnouncementController::class, 'download'])
        ->name('announcements.download');
    
    // Profile routes
    Route::get('profile', [GuruProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [GuruProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [GuruProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [GuruProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Settings routes
    Route::get('settings', [GuruSettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [GuruSettingsController::class, 'update'])->name('settings.update');

    // AJAX routes for dependent dropdowns are defined above with the controller
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
    Route::post('/submissions/{assignment}', [App\Http\Controllers\Siswa\SubmissionController::class, 'store'])->name('submissions.store');
    Route::put('/submissions/{submission}', [App\Http\Controllers\Siswa\SubmissionController::class, 'update'])->name('submissions.update');
    Route::delete('/submissions/{submission}', [App\Http\Controllers\Siswa\SubmissionController::class, 'destroy'])->name('submissions.destroy');
    
    // Submission routes
    Route::get('/submissions', [App\Http\Controllers\Siswa\SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{submission}', [App\Http\Controllers\Siswa\SubmissionController::class, 'show'])->name('submissions.show');
    Route::get('/submissions/{submission}/download', [App\Http\Controllers\Siswa\SubmissionController::class, 'download'])->name('submissions.download');
    Route::post('/assignments/{assignment}/submit', [App\Http\Controllers\Siswa\SubmissionController::class, 'store'])->name('submissions.store');
    Route::put('/submissions/{submission}', [App\Http\Controllers\Siswa\SubmissionController::class, 'update'])->name('submissions.update');
    Route::delete('/submissions/{submission}', [App\Http\Controllers\Siswa\SubmissionController::class, 'destroy'])->name('submissions.destroy');
    
    // Materials for students
    Route::resource('materials', App\Http\Controllers\Siswa\SiswaMaterialController::class)->only(['index', 'show']);
    
    // Schedule for students (view only)
    Route::get('/schedule', [App\Http\Controllers\Siswa\SiswaScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/schedule/day/{day}', [App\Http\Controllers\Siswa\SiswaScheduleController::class, 'showDay'])->name('schedule.day');
    Route::get('/schedule/export/ical', [App\Http\Controllers\Siswa\SiswaScheduleController::class, 'exportIcal'])->name('schedule.export-ical');
    
    // Grades for students
    Route::get('/grades', [App\Http\Controllers\Siswa\SiswaGradeController::class, 'index'])->name('grades.index');
    
    // Announcements for students
    Route::get('/announcements', [App\Http\Controllers\Siswa\SiswaAnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/{announcement}', [App\Http\Controllers\Siswa\SiswaAnnouncementController::class, 'show'])->name('announcements.show');
    Route::get('/announcements/{announcement}/download', [App\Http\Controllers\Siswa\SiswaAnnouncementController::class, 'download'])
        ->name('announcements.download');
    
    // Student Attendance Routes (read-only)
    Route::get('attendance', [App\Http\Controllers\Siswa\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/{month?}/{year?}', [App\Http\Controllers\Siswa\AttendanceController::class, 'month'])->name('attendance.month');
    Route::get('attendance/subject/{subject}', [App\Http\Controllers\Siswa\AttendanceController::class, 'bySubject'])->name('attendance.by-subject');
    
    // Add the profile route
    Route::get('/profile', [\App\Http\Controllers\Siswa\SiswaProfileController::class, 'show'])->name('profile.show');
    
    // Add settings routes
    Route::get('/settings', [\App\Http\Controllers\Siswa\SiswaSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Siswa\SiswaSettingsController::class, 'update'])->name('settings.update');

    // Student Submission Routes
    Route::post('/assignments/{assignment}/submit', [App\Http\Controllers\Siswa\SubmissionController::class, 'store'])->name('submissions.store');
    Route::put('/submissions/{submission}', [App\Http\Controllers\Siswa\SubmissionController::class, 'update'])->name('submissions.update');
    Route::delete('/submissions/{submission}', [App\Http\Controllers\Siswa\SubmissionController::class, 'destroy'])->name('submissions.destroy');
});

// Error routes
Route::fallback(function () {
    return view('errors.404');
});
