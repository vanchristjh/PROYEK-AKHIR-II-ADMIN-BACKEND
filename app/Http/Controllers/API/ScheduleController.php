<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Get all schedules
     */
    public function index(Request $request)
    {
        $query = ClassSchedule::with(['class', 'teacher'])
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('start_time');
            
        // Optional filters
        if ($request->has('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        
        if ($request->has('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }
        
        if ($request->has('day')) {
            $query->where('day_of_week', $request->day);
        }
        
        $schedules = $query->get();
        
        return response()->json([
            'success' => true,
            'schedules' => $schedules,
            'count' => $schedules->count()
        ]);
    }
    
    /**
     * Get schedules for the authenticated student
     */
    public function getStudentSchedules()
    {
        $user = Auth::user();
        
        if ($user->role !== 'student' || !$user->class_id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menemukan kelas untuk akun ini'
            ], 404);
        }
        
        $schedules = ClassSchedule::with(['teacher', 'class'])
            ->where('class_id', $user->class_id)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
            
        return response()->json([
            'success' => true,
            'schedules' => $schedules,
            'count' => $schedules->count()
        ]);
    }
    
    /**
     * Get schedules for the authenticated teacher
     */
    public function getTeacherSchedules()
    {
        $user = Auth::user();
        
        if ($user->role !== 'teacher') {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini bukan akun guru'
            ], 403);
        }
        
        $schedules = ClassSchedule::with(['class'])
            ->where('teacher_id', $user->id)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
            
        return response()->json([
            'success' => true,
            'schedules' => $schedules,
            'count' => $schedules->count()
        ]);
    }
}
