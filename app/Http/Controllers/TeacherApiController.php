<?php
// API route for retrieving teachers

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TeacherApiController extends Controller
{
    /**
     * Get all teachers for dropdown
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeachers(): JsonResponse
    {
        // Get teachers with role_id = 2
        $teachers = User::where('role_id', 2)->orderBy('name')->get(['id', 'name', 'email']);
        
        // If no teachers found with role_id=2, look for potential teachers with other attributes
        if ($teachers->isEmpty()) {
            $teachers = User::where(function($query) {
                    $query->whereNotNull('nip')
                        ->orWhere('email', 'like', '%guru%')
                        ->orWhere('email', 'like', '%teacher%')
                        ->orWhere('name', 'like', '%guru%')
                        ->orWhere('name', 'like', '%teacher%');
                })
                ->orderBy('name')
                ->get(['id', 'name', 'email']);
        }
        
        return response()->json([
            'success' => true,
            'teachers' => $teachers,
            'count' => $teachers->count()
        ]);
    }
}
