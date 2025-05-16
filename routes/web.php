<?php

use Illuminate\Support\Facades\Route;

// Import controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminSubjectController;
use App\Http\Controllers\Admin\AdminClassroomController;
use App\Http\Controllers\Admin\AdminAnnouncementController;
use App\Http\Controllers\Admin\DatabaseFixController;

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

use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Guru\ScheduleController as GuruScheduleController;
use App\Http\Controllers\Siswa\ScheduleController as SiswaScheduleController2; // Renamed to avoid conflict
use App\Http\Controllers\Admin\SubjectController;

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
    
    // Emergency login route for bypassing potential redirect loops
    Route::get('/emergency-login', [App\Http\Controllers\Auth\EmergencyLoginController::class, 'showLoginForm'])->name('emergency.login');
    Route::post('/emergency-login', [App\Http\Controllers\Auth\EmergencyLoginController::class, 'login'])->name('emergency.login.submit');
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

// Debug route for authentication status - remove in production
Route::get('/auth-debug', function() {
    return view('auth.debug');
});

// New debug routes
Route::get('/debug/auth', [App\Http\Controllers\DebugController::class, 'checkAuth'])->name('debug.auth');
Route::get('/debug/guru-route', [App\Http\Controllers\DebugController::class, 'tryGuruRoute'])->name('debug.guru-route');

// New comprehensive debugging tool
Route::get('/debug-auth', [App\Http\Controllers\AuthDebugController::class, 'debugAuth'])->name('debug.auth');

// Add password update route for authentication
Route::middleware('auth')->group(function () {
    Route::put('/password-update', [App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');
});

// Admin Routes
// Adding a special fallback route for admin login issues
Route::get('/admin/simple-dashboard', function() {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    $user = auth()->user();
    if (!$user->role || $user->role->slug !== 'admin') {
        return redirect()->route('unauthorized');
    }
    return view('admin.dashboard.debug', ['user' => $user]);
})->name('admin.simple-dashboard');

// Special route to break redirect loops for admin dashboard
Route::get('/admin/dashboard/fallback', function() {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    $user = auth()->user();
    // Clear any session values that might be causing loops
    session()->forget('redirect_count');
    session()->forget('url.intended');
    
    // Always show admin debug dashboard if we get here
    return view('admin.dashboard.debug', [
        'user' => $user,
        'debug_info' => ['message' => 'Using fallback dashboard to break redirect loop']
    ]);
})->name('admin.dashboard.fallback');

// Regular admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard routes - use a simplified approach first to avoid redirect loops
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [App\Http\Controllers\Admin\DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/activities', [App\Http\Controllers\Admin\DashboardController::class, 'getActivities'])->name('dashboard.activities');
    
    // User management
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    
    // Classroom management - remove duplicate routes
    Route::get('classrooms/export-all', [App\Http\Controllers\Admin\ClassroomController::class, 'exportAll'])
        ->name('classrooms.export-all');
    Route::delete('classrooms/{classroom}/students/{student}', [App\Http\Controllers\Admin\ClassroomController::class, 'removeStudent'])
        ->name('classrooms.removeStudent');
    Route::get('classrooms/{classroom}/export', [App\Http\Controllers\Admin\ClassroomController::class, 'export'])
        ->name('classrooms.export');
    Route::resource('classrooms', App\Http\Controllers\Admin\ClassroomController::class);
    
    // Subject management
    Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
    Route::get('subjects/{subject}/download/{format?}', [App\Http\Controllers\Admin\SubjectController::class, 'download'])
        ->name('subjects.download');
    Route::get('subjects/{subject}/teachers', [App\Http\Controllers\Admin\SubjectController::class, 'getTeachers'])->name('subjects.teachers');
    
    // Schedule management
    Route::get('schedule/calendar', [App\Http\Controllers\Admin\ScheduleController::class, 'calendar'])->name('schedule.calendar');
    Route::get('schedule/export', [App\Http\Controllers\Admin\ScheduleController::class, 'export'])->name('schedule.export');
    Route::get('schedule/bulk-create', [App\Http\Controllers\Admin\ScheduleController::class, 'bulkCreate'])->name('schedule.bulk-create');
    Route::post('schedule/bulk-store', [App\Http\Controllers\Admin\ScheduleController::class, 'bulkStore'])->name('schedule.bulk-store');
    Route::post('schedule/check-conflicts', [App\Http\Controllers\Admin\ScheduleController::class, 'checkConflicts'])->name('schedule.check-conflicts');
    Route::get('/admin/schedule/check-conflicts', 'App\Http\Controllers\Admin\ScheduleController@checkConflicts')->name('admin.schedule.check-conflicts');
    Route::resource('schedule', App\Http\Controllers\Admin\ScheduleController::class);
    
    // Schedule repair routes
    Route::get('/schedule/repair', [App\Http\Controllers\Admin\ScheduleRepairController::class, 'index'])->name('schedule.repair');
    Route::post('/schedule/repair', [App\Http\Controllers\Admin\ScheduleRepairController::class, 'repair'])->name('schedule.repair');
    Route::get('/schedule/clean-relations', [App\Http\Controllers\Admin\ScheduleRepairController::class, 'cleanNullRelations'])->name('schedule.clean-relations');
    
    // Announcement management
    Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class);
    Route::get('announcements/{announcement}/download', [App\Http\Controllers\Admin\AnnouncementController::class, 'download'])->name('announcements.download');
    Route::get('announcements/{announcement}/export/pdf', [App\Http\Controllers\Admin\AnnouncementController::class, 'exportPdf'])->name('announcements.export.pdf');
    Route::get('announcements/{announcement}/export/excel', [App\Http\Controllers\Admin\AnnouncementController::class, 'exportExcel'])->name('announcements.export.excel');

    // Profile routes
    Route::get('profile', [App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

    // Settings routes
    Route::get('settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    
    // Debug routes
    Route::get('/debug/schema', [\App\Http\Controllers\Admin\DebugController::class, 'schema'])->name('debug.schema');
    
    // Schedule routes
    Route::get('/schedule/check-conflicts', [App\Http\Controllers\Admin\ScheduleController::class, 'checkConflicts']);
    Route::get('/subjects/{subject}/teachers', [App\Http\Controllers\Admin\ScheduleController::class, 'getTeachersBySubject']);
    Route::resource('schedule', App\Http\Controllers\Admin\ScheduleController::class);

    // Add this route in the admin group
    Route::get('/admin/get-teachers', 'App\Http\Controllers\Admin\TeacherController@getTeachers')->name('admin.get-teachers');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    
    // Settings routes
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    
    // Schedule Routes
    Route::resource('schedule', AdminScheduleController::class);
    Route::get('/schedule/calendar', [AdminScheduleController::class, 'calendar'])->name('schedule.calendar');
    Route::get('/schedule/export', [AdminScheduleController::class, 'export'])->name('schedule.export');
    Route::get('/schedule/bulk-create', [AdminScheduleController::class, 'bulkCreate'])->name('schedule.bulk-create');
    Route::post('/schedule/bulk-store', [AdminScheduleController::class, 'bulkStore'])->name('schedule.bulk-store');
    Route::get('/schedule/check-conflicts', [AdminScheduleController::class, 'checkConflictsApi'])->name('schedule.check-conflicts');
    
    // API endpoint to get teachers for dropdown
    Route::get('/get-teachers', [App\Http\Controllers\TeacherApiController::class, 'getTeachers'])->name('get-teachers');
    Route::get('/schedule/repair', [AdminScheduleController::class, 'repair'])->name('schedule.repair');
});

// Guru Routes
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [App\Http\Controllers\Guru\GuruDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/refresh', [App\Http\Controllers\Guru\GuruDashboardController::class, 'refresh'])->name('dashboard.refresh');
    
    // Schedule routes for teachers with full CRUD
    Route::resource('schedule', GuruScheduleController::class);
    Route::get('/schedule', 'App\Http\Controllers\Guru\ScheduleController@index')->name('schedule.index');
    Route::get('/schedule/export/pdf', 'App\Http\Controllers\Guru\ScheduleController@exportPDF')->name('schedule.export.pdf');
    Route::get('/schedule/export/excel', 'App\Http\Controllers\Guru\ScheduleController@exportExcel')->name('schedule.export.excel');
    Route::get('/schedule/create', 'App\Http\Controllers\Guru\ScheduleController@create')->name('schedule.create');
    Route::post('/schedule', 'App\Http\Controllers\Guru\ScheduleController@store')->name('schedule.store');
    Route::get('/schedule/{id}/edit', 'App\Http\Controllers\Guru\ScheduleController@edit')->name('schedule.edit');
    Route::put('/schedule/{id}', 'App\Http\Controllers\Guru\ScheduleController@update')->name('schedule.update');
    Route::delete('/schedule/{id}', 'App\Http\Controllers\Guru\ScheduleController@destroy')->name('schedule.destroy');
    
    // Materials management
    Route::resource('materials', App\Http\Controllers\Guru\MaterialController::class);
    
    // Assignments management
    Route::resource('assignments', App\Http\Controllers\Guru\AssignmentController::class);
    Route::get('subjects/{id}/classrooms', [App\Http\Controllers\Guru\AssignmentController::class, 'getClassrooms']);
    
    // For teacher selection by subject - use the admin controller but with guru prefix
    Route::get('subjects/{subject}/teachers', [App\Http\Controllers\Admin\SubjectController::class, 'getTeachers'])->name('subjects.teachers');
    
    // Teacher Submission Management Routes
    Route::get('/assignments/{assignment}/submissions', [App\Http\Controllers\Guru\SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/assignments/{assignment}/submissions/{submission}', [App\Http\Controllers\Guru\SubmissionController::class, 'show'])->name('submissions.show');
    Route::put('/assignments/{assignment}/submissions/{submission}/grade', [App\Http\Controllers\Guru\SubmissionController::class, 'grade'])->name('submissions.grade');
    Route::get('/assignments/{assignment}/submissions/{submission}/download', [App\Http\Controllers\Guru\SubmissionController::class, 'download'])->name('submissions.download');
    Route::get('/assignments/{assignment}/submissions/{submission}/preview', [App\Http\Controllers\Guru\SubmissionController::class, 'preview'])->name('submissions.preview');
    Route::post('/assignments/{assignment}/submissions/mass-grade', [App\Http\Controllers\Guru\SubmissionController::class, 'massGrade'])->name('submissions.mass-grade');
    Route::post('/assignments/{assignment}/submissions/zero/{student}', [App\Http\Controllers\Guru\SubmissionController::class, 'markZero'])->name('submissions.zero');
    Route::get('/assignments/{assignment}/submissions/export', [App\Http\Controllers\Guru\SubmissionController::class, 'export'])->name('submissions.export');
    
    // Add a route for all submissions
    Route::get('all-submissions', [App\Http\Controllers\Guru\AllSubmissionsController::class, 'index'])->name('all-submissions.index');
    
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
    Route::get('profile', [App\Http\Controllers\Guru\ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [App\Http\Controllers\Guru\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [App\Http\Controllers\Guru\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [App\Http\Controllers\Guru\ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Settings routes
    Route::get('settings', [App\Http\Controllers\Guru\SettingsController::class, 'index'])->name('settings.index');
    Route::get('settings/privacy', [App\Http\Controllers\Guru\SettingsController::class, 'privacy'])->name('settings.privacy');
    
    // Help routes
    Route::get('help', [App\Http\Controllers\Guru\HelpController::class, 'index'])->name('help');
    Route::get('help/tutorial', [App\Http\Controllers\Guru\HelpController::class, 'tutorial'])->name('help.tutorial');
    Route::post('settings', [GuruSettingsController::class, 'update'])->name('settings.update');

    // AJAX routes for dependent dropdowns are defined above with the controller

    // Assignments routes
    Route::resource('assignments', GuruAssignmentController::class);

    // Submissions routes
    Route::put('submissions/{submission}', [App\Http\Controllers\Guru\SubmissionController::class, 'update'])->name('submissions.update');
    Route::get('submissions/{submission}/download', [App\Http\Controllers\Guru\SubmissionController::class, 'download'])->name('submissions.download');
});

// Teacher routes
Route::middleware(['auth', 'role:teacher'])->prefix('guru')->name('guru.')->group(function () {
    // Assignments
    Route::resource('assignments', GuruAssignmentController::class);
    Route::get('subjects/{id}/classrooms', [GuruAssignmentController::class, 'getClassrooms']);
    
    // Submissions
    Route::post('submissions/{id}/grade', [App\Http\Controllers\Guru\SubmissionController::class, 'grade'])->name('submissions.grade');
    Route::get('submissions/{id}/download', [App\Http\Controllers\Guru\SubmissionController::class, 'download'])->name('submissions.download');
});

// Siswa Routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [App\Http\Controllers\Siswa\SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/refresh', [App\Http\Controllers\Siswa\SiswaDashboardController::class, 'refresh'])->name('dashboard.refresh');
    
    // Assignments for students
    Route::resource('assignments', App\Http\Controllers\Siswa\AssignmentController::class)->only(['index', 'show']);
    
    // Submissions for students
    Route::resource('submissions', App\Http\Controllers\Siswa\SubmissionController::class)->only(['index', 'show', 'update']);
    Route::post('/submissions/{assignment}', [App\Http\Controllers\Siswa\SubmissionController::class, 'store'])->name('submissions.create');
    Route::put('/submissions/{submission}', [App\Http\Controllers\Siswa\SubmissionController::class, 'update'])->name('submissions.update');
    Route::delete('/submissions/{submission}', [App\Http\Controllers\Siswa\SubmissionController::class, 'destroy'])->name('submissions.destroy');
    Route::post('submissions', [SubmissionController::class, 'store'])->name('submissions.store');
    Route::put('submissions/{id}', [SubmissionController::class, 'update'])->name('submissions.update');
    Route::delete('submissions/{id}', [SubmissionController::class, 'destroy'])->name('submissions.destroy');
    Route::get('submissions/{id}/download', [SubmissionController::class, 'download'])->name('submissions.download');
    
    // Submission routes
    Route::get('/submissions', [App\Http\Controllers\Siswa\SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{id}', [App\Http\Controllers\Siswa\SubmissionController::class, 'show'])->name('submissions.view');
    Route::get('/submissions/{id}/download', [App\Http\Controllers\Siswa\SubmissionController::class, 'download'])->name('submissions.download');
    Route::post('/assignments/{assignment}/submit', [App\Http\Controllers\Siswa\SubmissionController::class, 'store'])->name('submissions.store');
    Route::put('/submissions/{id}', [App\Http\Controllers\Siswa\SubmissionController::class, 'update'])->name('submissions.edit');
    Route::delete('/submissions/{id}', [App\Http\Controllers\Siswa\SubmissionController::class, 'destroy'])->name('submissions.remove');
    
    // Materials for students - fix route naming to avoid conflicts
    Route::get('/materials', [App\Http\Controllers\Siswa\MaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/{material}', [App\Http\Controllers\Siswa\MaterialController::class, 'show'])->name('materials.show');
    
    // Add Materials routes
    Route::resource('materials', App\Http\Controllers\Siswa\MaterialController::class);
    
    // Schedule for students (view only)
    Route::get('/schedule', [App\Http\Controllers\Siswa\SiswaScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/schedule/day/{day}', [App\Http\Controllers\Siswa\SiswaScheduleController::class, 'showDay'])->name('schedule.day');
    Route::get('/schedule/export/ical', [App\Http\Controllers\Siswa\SiswaScheduleController::class, 'exportIcal'])->name('schedule.export-ical');
    Route::get('/schedules', [SiswaScheduleController2::class, 'index'])->name('schedule.index');
    Route::get('/schedules/day/{day}', [SiswaScheduleController2::class, 'showDay'])->name('schedule.day');
    
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

    // Student Submission Routes - these are already defined above, so commenting them out
    // Route::post('/assignments/{assignment}/submit', [App\Http\Controllers\Siswa\SubmissionController::class, 'store'])->name('submissions.store');
    // Route::put('/submissions/{submission}', [App\Http\Controllers\Siswa\SubmissionController::class, 'update'])->name('submissions.update');
    // Route::delete('/submissions/{submission}', [App\Http\Controllers\Siswa\SubmissionController::class, 'destroy'])->name('submissions.destroy');

    // Assignments routes for students
    Route::get('assignments', [App\Http\Controllers\Siswa\SiswaAssignmentController::class, 'index'])->name('assignments.index');
    Route::get('assignments/{assignment}', [App\Http\Controllers\Siswa\SiswaAssignmentController::class, 'show'])->name('assignments.show');
    Route::post('assignments/{assignment}/submit', [App\Http\Controllers\Siswa\SiswaAssignmentController::class, 'submit'])->name('assignments.submit');
});

// Temporary route to fix database schema issues (remove after using)
Route::get('/fix-database', function() {
    if (!Schema::hasTable('role_user')) {
        Schema::create('role_user', function ($table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['role_id', 'user_id']);
        });
        
        // Populate the table with existing user role data
        DB::statement('
            INSERT INTO role_user (role_id, user_id, created_at, updated_at)
            SELECT role_id, id, NOW(), NOW()
            FROM users
            WHERE role_id IS NOT NULL
        ');
        
        return redirect('/')->with('message', 'Database schema fixed successfully!');
    }
    
    return redirect('/')->with('message', 'Database schema already correct!');
})->middleware(['auth']);

// Database fix route - Remove or comment out after use
Route::get('/fix-database', [App\Http\Controllers\DatabaseFixController::class, 'checkAndFixSchedules']);

// Manual fix route - access this once to fix the database
// Make sure to remove or comment out this route after using it
Route::get('/fix-schedules-table', [App\Http\Controllers\ManualFixController::class, 'fixSchedulesTable']);

// Quick fix route - access this route once to add the missing column
// Remove or comment this route after using it
Route::get('/fix-schedules', [App\Http\Controllers\DatabaseFixController::class, 'fixSchedulesTable']);

// Temporary diagnostic route - remove after debugging
Route::get('/diagnostic/check-teacher/{id}', function ($id) {
    $teacher = DB::table('teachers')->where('id', $id)->first();
    $subjects = DB::table('subjects')->get();
    $subjectTeacher = DB::table('subject_teacher')->where('teacher_id', $id)->get();
    
    return [
        'teacher_exists' => !is_null($teacher),
        'teacher_data' => $teacher,
        'subject_count' => $subjects->count(),
        'existing_assignments' => $subjectTeacher
    ];
});

// Database fix routes
Route::get('/admin/fix-assignments-teacher-id', [DatabaseFixController::class, 'fixAssignmentsTeacherId'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.fix-assignments-teacher-id');

// Add this diagnostic route - REMOVE AFTER FIXING THE ISSUE!
Route::get('/diagnostic/teachers', function() {
    $teachers = \App\Models\User::where('role_id', 2)->get();
    $allUsers = \App\Models\User::all();
    
    return response()->json([
        'teacher_count' => $teachers->count(),
        'all_users_count' => $allUsers->count(),
        'teacher_role_exists' => \App\Models\Role::where('id', 2)->exists(),
        'roles' => \App\Models\Role::all()->pluck('name', 'id'),
        'sample_teachers' => $teachers->take(5)->map(function($teacher) {
            return [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'email' => $teacher->email,
                'role_id' => $teacher->role_id
            ];
        })
    ]);
});

// Error routes
Route::fallback(function () {
    return view('errors.404');
});

// At the bottom of your web.php file, add:
require __DIR__.'/debug.php';
require __DIR__.'/materials.php';
