<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ClassScheduleController;
use App\Http\Controllers\TeacherScheduleController;
use App\Http\Controllers\StudentScheduleController;
use App\Http\Controllers\AcademicCalendarController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TeacherAttendanceController;
use App\Http\Controllers\GradeCategoryController;
use App\Http\Controllers\GradeItemController;
use App\Http\Controllers\AcademicReportController;
use App\Http\Controllers\GradeController;

Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Debug route to check session
Route::get('/check-auth', function () {
    return [
        'authenticated' => auth()->check(),
        'user' => auth()->user() ? auth()->user()->only(['id', 'name', 'email', 'role']) : null,
        'session_id' => session()->getId(),
    ];
})->middleware('auth');

// Dashboard Routes
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Student Account Management Routes
Route::prefix('students')->middleware('auth')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('students.index');
    Route::get('/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/', [StudentController::class, 'store'])->name('students.store');
    Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
});

// Define export routes outside of any route groups for simplicity
Route::get('/students/export/excel', [App\Http\Controllers\StudentController::class, 'exportExcel'])->name('students.export.excel');
Route::get('/students/export/pdf', [App\Http\Controllers\StudentController::class, 'exportPdf'])->name('students.export.pdf');

// Teacher export routes
Route::get('/teachers/export/excel', [TeacherController::class, 'exportExcel'])->name('teachers.export.excel');
Route::get('/teachers/export/pdf', [TeacherController::class, 'exportPdf'])->name('teachers.export.pdf');

// Teacher Account Management Routes
Route::resource('students', StudentController::class);

// Teacher Account Management Routes
Route::prefix('teachers')->middleware('auth')->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/create', [TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
});

// Class Management Routes
Route::prefix('classes')->middleware('auth')->group(function () {
    Route::get('/', [ClassRoomController::class, 'index'])->name('classes.index');
    Route::get('/create', [ClassRoomController::class, 'create'])->name('classes.create');
    Route::post('/', [ClassRoomController::class, 'store'])->name('classes.store');
    Route::get('/{class}', [ClassRoomController::class, 'show'])->name('classes.show');
    Route::get('/{class}/edit', [ClassRoomController::class, 'edit'])->name('classes.edit');
    Route::put('/{class}', [ClassRoomController::class, 'update'])->name('classes.update');
    Route::delete('/{class}', [ClassRoomController::class, 'destroy'])->name('classes.destroy');
});

// Documentation Routes
Route::prefix('documentation')->middleware('auth')->group(function () {
    Route::get('/api', [DocumentationController::class, 'apiDocs'])->name('documentation.api');
    Route::get('/download', [DocumentationController::class, 'downloadGuide'])->name('documentation.download');
});

// Announcement routes
Route::prefix('announcements')->name('announcements.')->middleware(['auth'])->group(function () {
    Route::get('/', [AnnouncementController::class, 'index'])->name('index');
    Route::get('/create', [AnnouncementController::class, 'create'])->name('create');
    Route::post('/', [AnnouncementController::class, 'store'])->name('store');
    Route::get('/{announcement}', [AnnouncementController::class, 'show'])->name('show');
    Route::get('/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('edit');
    Route::put('/{announcement}', [AnnouncementController::class, 'update'])->name('update');
    Route::delete('/{announcement}', [AnnouncementController::class, 'destroy'])->name('destroy');
    Route::patch('/{announcement}/change-status', [AnnouncementController::class, 'changeStatus'])->name('change-status');
    Route::post('/{announcement}/mark-read', [AnnouncementController::class, 'markAsRead'])->name('mark-read');
    Route::get('/{announcement}/attachment', [AnnouncementController::class, 'downloadAttachment'])->name('attachment.download');
});

// Public announcements list
Route::get('/public-announcements', [AnnouncementController::class, 'list'])->name('announcements.list');

// Academic Calendar Routes
Route::prefix('academic-calendar')->name('academic-calendar.')->middleware('auth')->group(function () {
    Route::get('/', [AcademicCalendarController::class, 'index'])->name('index');
    Route::get('/create', [AcademicCalendarController::class, 'create'])->name('create');
    Route::post('/', [AcademicCalendarController::class, 'store'])->name('store');
    Route::get('/{academicCalendar}', [AcademicCalendarController::class, 'show'])->name('show');
    Route::get('/{academicCalendar}/edit', [AcademicCalendarController::class, 'edit'])->name('edit');
    Route::put('/{academicCalendar}', [AcademicCalendarController::class, 'update'])->name('update');
    Route::delete('/{academicCalendar}', [AcademicCalendarController::class, 'destroy'])->name('destroy');
    Route::get('/view/calendar', [AcademicCalendarController::class, 'calendar'])->name('calendar');
    Route::get('/view/upcoming', [AcademicCalendarController::class, 'upcoming'])->name('upcoming');
});

// Schedule Management Routes
Route::middleware(['auth'])->prefix('schedules')->name('schedules.')->group(function () {
    Route::get('/', [ClassScheduleController::class, 'index'])->name('index');
    Route::get('/create', [ClassScheduleController::class, 'create'])->name('create');
    Route::post('/', [ClassScheduleController::class, 'store'])->name('store');
    Route::get('/{schedule}', [ClassScheduleController::class, 'show'])->name('show');
    Route::get('/{schedule}/edit', [ClassScheduleController::class, 'edit'])->name('edit');
    Route::put('/{schedule}', [ClassScheduleController::class, 'update'])->name('update');
    Route::delete('/{schedule}', [ClassScheduleController::class, 'destroy'])->name('destroy');
    Route::get('/view/weekly', [ClassScheduleController::class, 'weekly'])->name('weekly');
});

// Teacher Schedule Routes
Route::middleware(['auth'])->prefix('teacher-schedules')->name('teacher-schedules.')->group(function () {
    Route::get('/', [TeacherScheduleController::class, 'index'])->name('index');
    Route::get('/weekly', [TeacherScheduleController::class, 'weekly'])->name('weekly');
    Route::get('/create', [TeacherScheduleController::class, 'create'])->name('create');
});

// Student Schedule Routes
Route::middleware(['auth'])->prefix('student-schedules')->name('student-schedules.')->group(function () {
    Route::get('/', [StudentScheduleController::class, 'index'])->name('index');
    Route::get('/weekly', [StudentScheduleController::class, 'weekly'])->name('weekly');
});

// Subjects Routes
Route::resource('subjects', SubjectsController::class);

// Attendance Routes
Route::prefix('attendance')->middleware('auth')->name('attendance.')->group(function () {
    Route::get('/', [AttendanceController::class, 'index'])->name('index');
    Route::get('/create', [AttendanceController::class, 'create'])->name('create');
    Route::post('/', [AttendanceController::class, 'store'])->name('store');
    Route::get('/{attendance}', [AttendanceController::class, 'show'])->name('show');
    Route::get('/{attendance}/edit', [AttendanceController::class, 'edit'])->name('edit');
    Route::put('/{attendance}', [AttendanceController::class, 'update'])->name('update');
    Route::delete('/{attendance}', [AttendanceController::class, 'destroy'])->name('destroy');
    Route::get('/report/view', [AttendanceController::class, 'report'])->name('report');
    Route::get('/{attendance}/export', [AttendanceController::class, 'export'])->name('export');
});

// Teacher Attendance Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('teacher-attendance', TeacherAttendanceController::class);
    Route::get('teacher-attendance-report', [TeacherAttendanceController::class, 'report'])->name('teacher-attendance.report');
    
    // Student routes
    Route::resource('students', StudentController::class);

    // Settings routes
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/account', [App\Http\Controllers\SettingsController::class, 'account'])->name('settings.account');
    Route::get('/settings/notifications', [App\Http\Controllers\SettingsController::class, 'notifications'])->name('settings.notifications');
    Route::get('/settings/appearance', [App\Http\Controllers\SettingsController::class, 'appearance'])->name('settings.appearance');
    Route::get('/settings/system', [App\Http\Controllers\SettingsController::class, 'system'])->name('settings.system');
    Route::put('/settings/update-account', [App\Http\Controllers\SettingsController::class, 'updateAccount'])->name('settings.update-account');
    Route::put('/settings/update-system', [App\Http\Controllers\SettingsController::class, 'updateSystem'])->name('settings.update-system');
    Route::put('/settings/update-appearance', [App\Http\Controllers\SettingsController::class, 'updateAppearance'])->name('settings.update-appearance');
    Route::put('/settings/update-notifications', [App\Http\Controllers\SettingsController::class, 'updateNotifications'])->name('settings.update-notifications');
    Route::post('/settings/create-backup', [App\Http\Controllers\SettingsController::class, 'createBackup'])->name('settings.create-backup');
    Route::delete('/settings/delete-account', [App\Http\Controllers\SettingsController::class, 'deleteAccount'])->name('settings.delete-account');
    
    // Grades routes
    Route::resource('grades', GradeController::class);

    // Academic Grade Routes
    Route::resource('grade-categories', GradeCategoryController::class);
    Route::resource('grade-items', GradeItemController::class);
    
    // Academic Reports
    Route::get('/academic-reports', [AcademicReportController::class, 'index'])->name('academic-reports.index');
    Route::get('/academic-reports/{id}', [AcademicReportController::class, 'show'])->name('academic-reports.show');
    Route::get('/academic-reports/{id}/print', [AcademicReportController::class, 'print'])->name('academic-reports.print');
});

// Notification routes
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/get', [App\Http\Controllers\NotificationController::class, 'getNotifications'])->name('notifications.get');
});

// Academic Grade Management
Route::resource('grade-categories', GradeCategoryController::class);
Route::resource('grade-items', GradeItemController::class);
Route::get('grade-items/{gradeItem}/enter-grades', [GradeItemController::class, 'enterGrades'])->name('grade-items.enter-grades');
Route::post('grade-items/{gradeItem}/save-grades', [GradeItemController::class, 'saveGrades'])->name('grade-items.save-grades');

// Academic Reports
Route::get('academic-reports', [AcademicReportController::class, 'index'])->name('academic-reports.index');
Route::post('academic-reports/export-pdf', [AcademicReportController::class, 'exportPdf'])->name('academic-reports.export-pdf');
Route::get('academic-reports/class-ranking', [AcademicReportController::class, 'classRanking'])->name('academic-reports.class-ranking');

// Academic Grades Routes
Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {
    Route::resource('grades', \App\Http\Controllers\GradeController::class);
    
    // Add this route to redirect from academic-grades.index to grades.index
    Route::get('academic-grades', function() {
        return redirect()->route('grades.index');
    })->name('academic-grades.index');
});
