<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\DocumentationController;

Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
