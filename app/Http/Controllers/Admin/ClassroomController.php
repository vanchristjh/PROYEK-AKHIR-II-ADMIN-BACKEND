<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display a listing of classrooms
     */
    public function index()
    {
        $classrooms = Classroom::with('homeroomTeacher')->latest()->paginate(10);
        
        return view('admin.classrooms.index', compact('classrooms'));
    }

    /**
     * Show the form for creating a new classroom
     */
    public function create()
    {
        $teachers = User::where('role_id', 2)->get(); // 2 = guru
        $subjects = Subject::all();
        
        return view('admin.classrooms.create', compact('teachers', 'subjects'));
    }

    /**
     * Store a newly created classroom
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'grade_level' => ['required', 'string', 'max:20'],
            'academic_year' => ['required', 'string', 'max:20'],
            'homeroom_teacher_id' => ['required', 'exists:users,id'],
            'capacity' => ['required', 'integer', 'min:1'],
            'room_number' => ['nullable', 'string', 'max:20'],
            'subjects' => ['nullable', 'array'],
            'subjects.*' => ['exists:subjects,id'],
        ]);
        
        $classroom = Classroom::create([
            'name' => $validated['name'],
            'grade_level' => $validated['grade_level'],
            'academic_year' => $validated['academic_year'],
            'homeroom_teacher_id' => $validated['homeroom_teacher_id'],
            'capacity' => $validated['capacity'],
            'room_number' => $validated['room_number'] ?? null,
        ]);
        
        // Assign subjects if provided
        if (!empty($validated['subjects'])) {
            $classroom->subjects()->attach($validated['subjects']);
        }
        
        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Classroom created successfully!');
    }

    /**
     * Show classroom details
     */
    public function show(Classroom $classroom)
    {
        $classroom->load('homeroomTeacher', 'subjects', 'students');
        
        return view('admin.classrooms.show', compact('classroom'));
    }

    /**
     * Show the form for editing a classroom
     */
    public function edit(Classroom $classroom)
    {
        $teachers = User::where('role_id', 2)->get(); // 2 = guru
        $subjects = Subject::all();
        $assignedSubjects = $classroom->subjects()->pluck('subject_id')->toArray();
        
        return view('admin.classrooms.edit', compact('classroom', 'teachers', 'subjects', 'assignedSubjects'));
    }

    /**
     * Update the specified classroom
     */
    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'grade_level' => ['required', 'string', 'max:20'],
            'academic_year' => ['required', 'string', 'max:20'],
            'homeroom_teacher_id' => ['required', 'exists:users,id'],
            'capacity' => ['required', 'integer', 'min:1'],
            'room_number' => ['nullable', 'string', 'max:20'],
            'subjects' => ['nullable', 'array'],
            'subjects.*' => ['exists:subjects,id'],
        ]);
        
        $classroom->update([
            'name' => $validated['name'],
            'grade_level' => $validated['grade_level'],
            'academic_year' => $validated['academic_year'],
            'homeroom_teacher_id' => $validated['homeroom_teacher_id'],
            'capacity' => $validated['capacity'],
            'room_number' => $validated['room_number'] ?? null,
        ]);
        
        // Sync subjects
        $classroom->subjects()->sync($validated['subjects'] ?? []);
        
        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Classroom updated successfully!');
    }

    /**
     * Remove the specified classroom
     */
    public function destroy(Classroom $classroom)
    {
        // Detach all subjects
        $classroom->subjects()->detach();
        
        // Set classroom_id to null for all students in this classroom
        User::where('classroom_id', $classroom->id)
            ->update(['classroom_id' => null]);
        
        $classroom->delete();
        
        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Classroom deleted successfully!');
    }
}
