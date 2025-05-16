<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ScheduleConflictController extends Controller
{
    /**
     * Check for potential schedule conflicts
     */    public function check(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $conflicts = [];
        $schedule_id = $request->input('schedule_id');

        // Check classroom schedule conflicts
        $classroomConflicts = Schedule::where('classroom_id', $request->classroom_id)
            ->where('day', $request->day)
            ->when($schedule_id, function ($query) use ($schedule_id) {
                return $query->where('id', '!=', $schedule_id);
            })
            ->where(function ($query) use ($request) {
                // Time overlap check
                $query->where(function ($q) use ($request) {
                    $q->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
                });
            })
            ->get();

        foreach ($classroomConflicts as $conflict) {
            $conflicts[] = [
                'type' => 'classroom',
                'message' => "Konflik: Kelas {$conflict->classroom->name} sudah dijadwalkan untuk mata pelajaran {$conflict->subject->name} pada {$conflict->day}, {$conflict->start_time}-{$conflict->end_time}."
            ];
        }

        // Check teacher schedule conflicts
        $teacherConflicts = Schedule::where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)
            ->when($schedule_id, function ($query) use ($schedule_id) {
                return $query->where('id', '!=', $schedule_id);
            })
            ->where(function ($query) use ($request) {
                // Time overlap check
                $query->where(function ($q) use ($request) {
                    $q->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
                });
            })
            ->get();

        foreach ($teacherConflicts as $conflict) {
            $conflicts[] = [
                'type' => 'teacher',
                'message' => "Konflik: Guru {$conflict->teacher->name} sudah mengajar di kelas {$conflict->classroom->name} pada {$conflict->day}, {$conflict->start_time}-{$conflict->end_time}."
            ];
        }

        return response()->json([
            'conflicts' => $conflicts,
            'has_conflicts' => count($conflicts) > 0
        ]);
    }
}
