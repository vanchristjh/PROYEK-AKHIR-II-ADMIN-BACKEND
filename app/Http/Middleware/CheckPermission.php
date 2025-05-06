<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handles permission checking across roles
     * 
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Admin has all permissions
        if ($user->isAdmin()) {
            return $next($request);
        }
        
        // Resource-based permissions
        if (str_contains($permission, ':')) {
            [$resource, $action] = explode(':', $permission);
            
            // Handle resource-specific permissions
            if ($resource === 'grade') {
                // Teachers can manage grades they created
                if ($action === 'edit' || $action === 'delete') {
                    $gradeId = $request->route('grade');
                    $grade = \App\Models\Grade::find($gradeId);
                    
                    if ($grade && $user->isTeacher() && $grade->teacher_id === $user->id) {
                        return $next($request);
                    }
                }
                
                // Students can only view their own grades
                if ($action === 'view' && $user->isStudent()) {
                    $gradeId = $request->route('grade');
                    $grade = \App\Models\Grade::find($gradeId);
                    
                    if ($grade && $grade->student_id === $user->id) {
                        return $next($request);
                    }
                }
            } 
            elseif ($resource === 'assignment') {
                // Teachers can manage their assignments
                if ($user->isTeacher() && in_array($action, ['edit', 'delete', 'view'])) {
                    $assignmentId = $request->route('assignment');
                    $assignment = \App\Models\Assignment::find($assignmentId);
                    
                    if ($assignment && $assignment->teacher_id === $user->id) {
                        return $next($request);
                    }
                }
                
                // Students can view assignments for their class
                if ($user->isStudent() && $action === 'view') {
                    $assignmentId = $request->route('assignment');
                    $assignment = \App\Models\Assignment::find($assignmentId);
                    
                    if ($assignment && $assignment->classroom_id === $user->classroom_id) {
                        return $next($request);
                    }
                }
            }
            // Add more resource handlers as needed
        }
        
        // Role-based permissions
        else {
            $rolePermissions = [
                'guru' => [
                    'create-assignment', 'manage-materials', 'grade-submissions', 
                    'record-attendance', 'create-announcement'
                ],
                'siswa' => [
                    'view-assignments', 'view-materials', 'view-grades', 
                    'submit-assignment', 'view-schedule'
                ]
            ];
            
            if ($user->isTeacher() && in_array($permission, $rolePermissions['guru'])) {
                return $next($request);
            }
            
            if ($user->isStudent() && in_array($permission, $rolePermissions['siswa'])) {
                return $next($request);
            }
        }
        
        return redirect()->route('unauthorized');
    }
}
