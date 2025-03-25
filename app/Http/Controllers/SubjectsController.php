<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subject::query();

        // Apply filters
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('class_level')) {
            $query->where('class_level', $request->class_level);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Default sort by name, or user specified
        $orderBy = $request->order_by ?? 'name';
        $orderDirection = $request->direction ?? 'asc';
        $query->orderBy($orderBy, $orderDirection);

        $subjects = $query->paginate(10);

        return view('dashboard.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        return view('dashboard.subjects.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20|unique:subjects,code',
            'description' => 'nullable|string',
            'class_level' => 'nullable|string|max:10',
            'semester' => 'nullable|string|max:10',
            'curriculum' => 'nullable|string|max:100',
            'is_active' => 'sometimes|boolean',
            'credits' => 'nullable|integer|min:0',
            'subject_type' => 'nullable|string|max:50',
            'teacher_ids' => 'nullable|array',
            'teacher_ids.*' => 'exists:users,id',
        ]);

        // Extract teacher IDs from validated data
        $teacherIds = $validated['teacher_ids'] ?? [];
        unset($validated['teacher_ids']);

        DB::beginTransaction();
        try {
            // Create subject
            $subject = Subject::create($validated);

            // Assign teachers
            if (!empty($teacherIds)) {
                $subject->teachers()->attach($teacherIds);
            }
            
            DB::commit();
            return redirect()->route('subjects.index')
                ->with('success', 'Mata pelajaran berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        $subject->load('teachers');
        return view('dashboard.subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $assignedTeachers = $subject->teachers->pluck('id')->toArray();
        
        return view('dashboard.subjects.edit', compact('subject', 'teachers', 'assignedTeachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['nullable', 'string', 'max:20', Rule::unique('subjects')->ignore($subject->id)],
            'description' => 'nullable|string',
            'class_level' => 'nullable|string|max:10',
            'semester' => 'nullable|string|max:10',
            'curriculum' => 'nullable|string|max:100',
            'is_active' => 'sometimes|boolean',
            'credits' => 'nullable|integer|min:0',
            'subject_type' => 'nullable|string|max:50',
            'teacher_ids' => 'nullable|array',
            'teacher_ids.*' => 'exists:users,id',
        ]);

        // Extract teacher IDs from validated data
        $teacherIds = $validated['teacher_ids'] ?? [];
        unset($validated['teacher_ids']);

        DB::beginTransaction();
        try {
            // Update subject
            $subject->update($validated);

            // Sync teachers
            $subject->teachers()->sync($teacherIds);
            
            DB::commit();
            return redirect()->route('subjects.index')
                ->with('success', 'Mata pelajaran berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        try {
            // Check if subject is used in schedules
            if ($subject->schedules()->count() > 0) {
                return back()->withErrors(['error' => 'Mata pelajaran ini tidak dapat dihapus karena masih digunakan dalam jadwal.']);
            }

            // Detach related teachers first
            $subject->teachers()->detach();
            
            // Delete the subject
            $subject->delete();
            
            return redirect()->route('subjects.index')
                ->with('success', 'Mata pelajaran berhasil dihapus');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
