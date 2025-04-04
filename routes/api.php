<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\TeacherController;
use App\Http\Controllers\API\TestController;
use App\Http\Controllers\API\ScheduleController;
use App\Http\Controllers\API\AnnouncementController;
use App\Http\Controllers\API\AcademicCalendarController;
use App\Http\Controllers\API\DashboardController;

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

// Test route to verify the API is working
Route::get('/test', [TestController::class, 'publicTest']);

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes requiring authentication
Route::middleware('auth:sanctum')->group(function () {
    // Authentication test route
    Route::get('/auth-test', [TestController::class, 'authTest']);
    
    // User routes
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Student management
    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/students/{id}', [StudentController::class, 'show']);
    Route::put('/students/{id}', [StudentController::class, 'update']);
    Route::post('/students/profile-photo', [StudentController::class, 'uploadProfilePhoto']);
    
    // Teacher management
    Route::get('/teachers', [TeacherController::class, 'index']);
    Route::get('/teachers/{id}', [TeacherController::class, 'show']);
    Route::put('/teachers/{id}', [TeacherController::class, 'update']);
    Route::post('/teachers/profile-photo', [TeacherController::class, 'uploadProfilePhoto']);
    Route::get('/teachers/{id}/subject', [TeacherController::class, 'getSubject']);
    
    // Schedule management
    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::get('/schedules/student', [ScheduleController::class, 'getStudentSchedules']);
    Route::get('/schedules/teacher', [ScheduleController::class, 'getTeacherSchedules']);
    
    // Announcements
    Route::get('/announcements', [AnnouncementController::class, 'getAnnouncements']);
    
    // Academic Calendar
    Route::get('/academic-calendar', [AcademicCalendarController::class, 'getEvents']);
    Route::get('/academic-calendar/upcoming', [AcademicCalendarController::class, 'getUpcomingEvents']);
    
    // Dashboard data
    Route::get('/dashboard', [DashboardController::class, 'index']);
});