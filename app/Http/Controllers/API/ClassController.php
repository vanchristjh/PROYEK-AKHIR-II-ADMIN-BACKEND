<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    /**
     * Get students in a specific class.
     *
     * @param int $classId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudents($classId)
    {
        try {
            // Check if the class exists
            $class = ClassRoom::findOrFail($classId);
            
            // Get all students in this class
            $students = User::where('role', 'student')
                ->where('class_id', $classId)
                ->orderBy('name')
                ->select('id', 'name', 'nis', 'nisn', 'profile_photo')
                ->get();
            
            return response()->json($students);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve students: ' . $e->getMessage()], 500);
        }
    }
}
