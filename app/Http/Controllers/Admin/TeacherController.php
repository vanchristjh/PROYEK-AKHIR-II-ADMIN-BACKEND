<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;

class TeacherController extends Controller
{
    /**
     * Get all teachers for AJAX requests
     */
    public function getTeachers()
    {
        try {
            // Try to get teachers from Teacher model
            $teachers = \App\Models\Teacher::all();
            
            // If no teachers found, get from User model with teacher role
            if ($teachers->isEmpty()) {
                $teacherRole = \App\Models\Role::where('name', 'teacher')->first();
                if ($teacherRole) {
                    $teacherUsers = \App\Models\User::where('role_id', $teacherRole->id)->get();
                    
                    // Map user data to teacher format
                    $teachers = $teacherUsers->map(function($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'source' => 'user'
                        ];
                    });
                }
            } else {
                // Add source information to existing teachers
                $teachers = $teachers->map(function($teacher) {
                    $teacher->source = 'teacher';
                    return $teacher;
                });
            }
            
            // If still empty, add a default teacher as fallback
            if ($teachers->isEmpty()) {
                $teachers = [[
                    'id' => 2,
                    'name' => 'Guru Default',
                    'source' => 'fallback'
                ]];
            }
            
            return response()->json([
                'success' => true,
                'teachers' => $teachers,
                'count' => count($teachers)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching teachers: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}