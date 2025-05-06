<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    /**
     * Display a listing of subjects
     */
    public function index()
    {
        $subjects = Subject::latest()->paginate(10);
        
        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new subject
     */
    public function create()
    {
        $teachers = User::where('role_id', 2)->get(); // 2 = guru
        
        return view('admin.subjects.create', compact('teachers'));
    }

    /**
     * Store a newly created subject
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:subjects'],
            'description' => ['nullable', 'string'],
            'teachers' => ['nullable', 'array'],
            'teachers.*' => ['exists:users,id,role_id,2'], // Ensure we only attach teacher users
        ]);
        
        $subject = Subject::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'description' => $validated['description'] ?? null,
        ]);
        
        // Assign teachers if provided
        if (!empty($validated['teachers'])) {
            $subject->teachers()->attach($validated['teachers']);
        }
        
        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created successfully!');
    }

    /**
     * Show the form for editing a subject
     */
    public function edit(Subject $subject)
    {
        $teachers = User::where('role_id', 2)->get(); // 2 = guru
        $assignedTeachers = $subject->teachers()->pluck('user_id')->toArray();
        
        return view('admin.subjects.edit', compact('subject', 'teachers', 'assignedTeachers'));
    }

    /**
     * Update the specified subject
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', Rule::unique('subjects')->ignore($subject->id)],
            'description' => ['nullable', 'string'],
            'teachers' => ['nullable', 'array'],
            'teachers.*' => ['exists:users,id'],
        ]);
        
        $subject->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'description' => $validated['description'] ?? null,
        ]);
        
        // Sync teachers
        $subject->teachers()->sync($validated['teachers'] ?? []);
        
        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully!');
    }

    /**
     * Remove the specified subject
     */
    public function destroy(Subject $subject)
    {
        // Detach all teachers
        $subject->teachers()->detach();
        
        $subject->delete();
        
        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully!');
    }
}
