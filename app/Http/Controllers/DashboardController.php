<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Material;
use App\Models\Subject;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function adminDashboard()
    {
        try {
            // Get statistics for admin dashboard
            $stats = [
                'students' => User::where('role_id', 3)->count(), // 3 = siswa
                'teachers' => User::where('role_id', 2)->count(), // 2 = guru
                'classes' => Classroom::count(),
                'subjects' => Subject::count(),
            ];
        } catch (\Exception $e) {
            Log::error('Error loading admin dashboard stats: ' . $e->getMessage());
            
            // Set default values if there's an error
            $stats = [
                'students' => 0,
                'teachers' => 0,
                'classes' => 0,
                'subjects' => 0,
            ];
        }
        
        return view('dashboard.admin', compact('stats'));
    }
    
    /**
     * Show the teacher dashboard
     */
    public function guruDashboard()
    {
        try {
            $userId = Auth::id();
            
            // Get statistics for teacher dashboard
            $stats = [
                'subjects' => Subject::whereHas('teachers', function($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->count(),
                'classes' => Classroom::whereHas('subjects.teachers', function($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->count(),
                'assignments' => Assignment::where('teacher_id', $userId)->count(),
                'materials' => Material::where('teacher_id', $userId)->count(),
            ];
            
            // Get recent submissions for display
            $recentSubmissions = Submission::whereHas('assignment', function($query) use ($userId) {
                $query->where('teacher_id', $userId);
            })
            ->with(['assignment.classroom', 'assignment.subject', 'student'])
            ->latest('submitted_at')
            ->take(5)
            ->get();
        } catch (\Exception $e) {
            Log::error('Error loading guru dashboard stats: ' . $e->getMessage());
            
            // Set default values if there's an error
            $stats = [
                'subjects' => 0,
                'classes' => 0,
                'assignments' => 0,
                'materials' => 0,
            ];
            
            $recentSubmissions = collect();
        }
        
        return view('dashboard.guru', compact('stats', 'recentSubmissions'));
    }
    
    /**
     * Show the student dashboard
     */
    public function siswaDashboard()
    {
        try {
            $userId = Auth::id();
            $classroomId = Auth::user()->classroom_id;
            
            // Default statistics for all students
            $stats = [
                'subjects' => 0,
                'assignments' => 0,
                'completedAssignments' => 0,
                'materials' => 0,
            ];
            
            $upcomingAssignments = collect();
            $recentMaterials = collect();
            
            // If student is assigned to a classroom, get real statistics
            if ($classroomId) {
                // Count subjects in student's class
                $stats['subjects'] = Subject::whereHas('classrooms', function($query) use ($classroomId) {
                    $query->where('classroom_id', $classroomId);
                })->count();
                
                // Count assignments for the student's class
                $stats['assignments'] = Assignment::where('classroom_id', $classroomId)->count();
                
                // Count completed assignments by this student
                $stats['completedAssignments'] = Submission::where('student_id', $userId)->count();
                
                // Count available materials
                $stats['materials'] = Material::where('classroom_id', $classroomId)
                    ->where('publish_date', '<=', now())
                    ->count();
                    
                // Get upcoming assignments ordered by deadline
                $upcomingAssignments = Assignment::where('classroom_id', $classroomId)
                    ->where('deadline', '>=', now()->subDays(1)) // Include assignments due today or in the future
                    ->with(['subject'])
                    ->orderBy('deadline')
                    ->take(5)
                    ->get()
                    ->map(function ($assignment) {
                        // Add a property to check if the assignment is close to deadline
                        $assignment->remainingDays = $assignment->deadline->diffInDays(now());
                        return $assignment;
                    });
                    
                // Get recent materials
                $recentMaterials = Material::where('classroom_id', $classroomId)
                    ->where('publish_date', '<=', now())
                    ->with('subject')
                    ->latest('publish_date')
                    ->take(5)
                    ->get();
            }
        } catch (\Exception $e) {
            Log::error('Error loading siswa dashboard stats: ' . $e->getMessage());
            
            // Set default values if there's an error
            $stats = [
                'subjects' => 0,
                'assignments' => 0,
                'completedAssignments' => 0,
                'materials' => 0,
            ];
            
            $upcomingAssignments = collect();
            $recentMaterials = collect();
        }
        
        return view('dashboard.siswa', compact('stats', 'upcomingAssignments', 'recentMaterials'));
    }
}
