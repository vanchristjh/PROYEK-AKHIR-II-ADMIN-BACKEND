<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleRepairController extends Controller
{
    public function index()
    {
        // Find schedules with broken relationships
        $brokenSchedules = Schedule::whereHas('teacher', function($query) {
                return $query;
            }, '<', 1)
            ->whereNotNull('teacher_id')
            ->orWhereHas('classroom', function($query) {
                return $query;
            }, '<', 1)
            ->whereNotNull('classroom_id')
            ->orWhereHas('subject', function($query) {
                return $query;
            }, '<', 1)
            ->whereNotNull('subject_id')
            ->get();
            
        // Get available teachers, classrooms, and subjects for repair options
        $teachers = Teacher::all();
        $classrooms = Classroom::all();
        $subjects = Subject::all();
            
        return view('admin.schedule.repair', compact('brokenSchedules', 'teachers', 'classrooms', 'subjects'));
    }
    
    public function repair(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $scheduleId = $request->input('schedule_id');
            $teacherId = $request->input('teacher_id');
            $classroomId = $request->input('classroom_id');
            $subjectId = $request->input('subject_id');
            
            $schedule = Schedule::findOrFail($scheduleId);
            
            // Update relationships as needed
            if ($teacherId) {
                $schedule->teacher_id = $teacherId;
            }
            
            if ($classroomId) {
                $schedule->classroom_id = $classroomId;
            }
            
            if ($subjectId) {
                $schedule->subject_id = $subjectId;
            }
            
            $schedule->save();
            
            DB::commit();
            
            return redirect()->route('admin.schedule.repair')
                ->with('success', 'Jadwal berhasil diperbaiki!');
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error repairing schedule: " . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal memperbaiki jadwal: ' . $e->getMessage());
        }
    }
    
    public function cleanNullRelations()
    {
        try {
            DB::beginTransaction();
            
            // For schedules with non-existent teacher IDs, set to null
            $updated = Schedule::whereHas('teacher', function($query) {
                return $query;
            }, '<', 1)
            ->whereNotNull('teacher_id')
            ->update(['teacher_id' => null]);
            
            DB::commit();
            
            return redirect()->route('admin.schedule.repair')
                ->with('success', 'Berhasil membersihkan ' . $updated . ' referensi ID guru yang tidak valid.');
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error cleaning null relations: " . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal membersihkan relasi: ' . $e->getMessage());
        }
    }
}
