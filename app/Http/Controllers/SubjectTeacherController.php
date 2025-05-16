<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class SubjectTeacherController extends Controller
{
    public function assignTeacher(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id', // Validate teacher exists
        ]);

        try {
            // Begin a database transaction to ensure data integrity
            DB::beginTransaction();
            
            // Double check if teacher exists in database
            $teacher = Teacher::find($request->teacher_id);
            if (!$teacher) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Guru dengan ID ' . $request->teacher_id . ' tidak ditemukan.');
            }
            
            // Double check if subject exists
            $subject = Subject::find($request->subject_id);
            if (!$subject) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Mata pelajaran dengan ID ' . $request->subject_id . ' tidak ditemukan.');
            }
            
            // Check if relationship already exists to avoid duplicates
            if (!$subject->teachers()->where('teacher_id', $teacher->id)->exists()) {
                $subject->teachers()->attach($teacher->id);
                DB::commit();
                return redirect()->back()->with('success', 'Guru berhasil ditugaskan ke mata pelajaran.');
            }
            
            DB::commit();
            return redirect()->back()->with('info', 'Guru sudah ditugaskan ke mata pelajaran ini sebelumnya.');
        } catch (Exception $e) {
            DB::rollBack();
            // Log the full error for debugging
            \Log::error('Error assigning teacher: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal menugaskan guru: ' . $e->getMessage());
        }
    }
}