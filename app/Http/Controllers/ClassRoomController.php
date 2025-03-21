<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    /**
     * Display a listing of the classes.
     */
    public function index()
    {
        $classes = ClassRoom::with('teacher')->get();
        
        // Group classes by level
        $classGroups = [
            'X' => $classes->where('level', 'X'),
            'XI' => $classes->where('level', 'XI'),
            'XII' => $classes->where('level', 'XII'),
        ];

        return view('dashboard.classes.index', compact('classGroups'));
    }

    /**
     * Show the form for creating a new class.
     */
    public function create()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('dashboard.classes.create', compact('teachers'));
    }

    /**
     * Store a newly created class in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|in:X,XI,XII',
            'type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:40',
            'teacher_id' => 'nullable|exists:users,id',
            'room' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $class = ClassRoom::create($validated);

        return redirect()->route('classes.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * Display the specified class.
     */
    public function show(ClassRoom $class)
    {
        $class->load(['teacher', 'students']);
        return view('dashboard.classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified class.
     */
    public function edit(ClassRoom $class)
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('dashboard.classes.edit', compact('class', 'teachers'));
    }

    /**
     * Update the specified class in storage.
     */
    public function update(Request $request, ClassRoom $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|in:X,XI,XII',
            'type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:40',
            'teacher_id' => 'nullable|exists:users,id',
            'room' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $class->update($validated);

        return redirect()->route('classes.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified class from storage.
     */
    public function destroy(ClassRoom $class)
    {
        // Update any students in this class to have null class_id
        User::where('class_id', $class->id)->update(['class_id' => null]);
        
        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
