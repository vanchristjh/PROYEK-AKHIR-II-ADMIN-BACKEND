<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AnnouncementController;
use App\Http\Controllers\API\AssignmentController;
use App\Http\Controllers\API\SubmissionController;
use App\Http\Controllers\API\MaterialController;
use App\Http\Controllers\API\ScheduleController;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\GradeController;
use App\Http\Controllers\API\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // User profile
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/profile', [UserController::class, 'updateProfile']);
    Route::post('/change-password', [UserController::class, 'changePassword']);
    Route::post('/upload-avatar', [UserController::class, 'uploadAvatar']);
    
    // Announcements
    Route::get('/announcements', [AnnouncementController::class, 'index']);
    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show']);
    Route::get('/announcements/{announcement}/download', [AnnouncementController::class, 'download']);
    
    // Material resources
    Route::get('/materials', [MaterialController::class, 'index']);
    Route::get('/materials/{material}', [MaterialController::class, 'show']);
    Route::get('/materials/{material}/download', [MaterialController::class, 'download']);
    
    // Schedule
    Route::get('/schedule', [ScheduleController::class, 'index']);
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/mark-read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);
    Route::post('/fcm-token', [NotificationController::class, 'storeFcmToken']);
    
    // Role-specific routes
    Route::middleware('role:siswa')->group(function () {
        // Student assignments
        Route::get('/student/assignments', [AssignmentController::class, 'studentIndex']);
        Route::get('/student/assignments/{assignment}', [AssignmentController::class, 'studentShow']);
        
        // Submissions
        Route::get('/student/submissions', [SubmissionController::class, 'index']);
        Route::get('/student/submissions/{submission}', [SubmissionController::class, 'show']);
        Route::post('/student/assignments/{assignment}/submit', [SubmissionController::class, 'store']);
        Route::post('/student/submissions/{submission}', [SubmissionController::class, 'update']);
        
        // Student grades
        Route::get('/student/grades', [GradeController::class, 'studentIndex']);
        
        // Student attendance
        Route::get('/student/attendance', [AttendanceController::class, 'studentIndex']);
        Route::get('/student/attendance/{month}/{year}', [AttendanceController::class, 'studentMonth']);
    });
    
    Route::middleware('role:guru')->group(function () {
        // Teacher assignments
        Route::get('/teacher/assignments', [AssignmentController::class, 'teacherIndex']);
        Route::post('/teacher/assignments', [AssignmentController::class, 'store']);
        Route::get('/teacher/assignments/{assignment}', [AssignmentController::class, 'teacherShow']);
        Route::put('/teacher/assignments/{assignment}', [AssignmentController::class, 'update']);
        Route::delete('/teacher/assignments/{assignment}', [AssignmentController::class, 'destroy']);
        
        // Teacher materials
        Route::get('/teacher/materials', [MaterialController::class, 'teacherIndex']);
        Route::post('/teacher/materials', [MaterialController::class, 'store']);
        Route::put('/teacher/materials/{material}', [MaterialController::class, 'update']);
        Route::delete('/teacher/materials/{material}', [MaterialController::class, 'destroy']);
        
        // Submissions for teachers
        Route::get('/teacher/assignments/{assignment}/submissions', [SubmissionController::class, 'assignmentSubmissions']);
        Route::get('/teacher/submissions/{submission}', [SubmissionController::class, 'teacherShow']);
        Route::post('/teacher/submissions/{submission}/grade', [SubmissionController::class, 'grade']);
        
        // Attendance management
        Route::get('/teacher/attendance/classes', [AttendanceController::class, 'getTeacherClasses']);
        Route::get('/teacher/attendance/class/{classroom}/date/{date}', [AttendanceController::class, 'getClassAttendance']);
        Route::post('/teacher/attendance/record', [AttendanceController::class, 'recordAttendance']);
        
        // Announcements for teachers
        Route::post('/teacher/announcements', [AnnouncementController::class, 'store']);
        Route::put('/teacher/announcements/{announcement}', [AnnouncementController::class, 'update']);
        Route::delete('/teacher/announcements/{announcement}', [AnnouncementController::class, 'destroy']);
    });
    
    Route::middleware('role:admin')->group(function () {
        // Admin user management
        Route::get('/admin/users', [UserController::class, 'index']);
        Route::post('/admin/users', [UserController::class, 'store']);
        Route::get('/admin/users/{user}', [UserController::class, 'show']);
        Route::put('/admin/users/{user}', [UserController::class, 'update']);
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy']);
        
        // Other admin features
        // ...
    });
});

// Test route for subject classrooms
Route::get('/subjects/{subject}/classrooms', function ($subjectId) {
    // Get the subject
    $subject = App\Models\Subject::find($subjectId);
    
    // Check if subject exists
    if (!$subject) {
        return response()->json(['error' => 'Subject not found'], 404);
    }
    
    // Get classrooms for this subject
    $classrooms = $subject->classrooms ?? [];
    
    // Return classrooms as JSON
    return response()->json($classrooms);
});