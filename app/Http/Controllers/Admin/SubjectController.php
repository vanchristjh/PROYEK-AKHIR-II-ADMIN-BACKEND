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
    public function index(Request $request)
    {
        $query = Subject::query();
        
        // Add search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('code', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }
        
        $subjects = $query->latest()->paginate(10);
        $subjects->appends($request->all()); // Keep search parameters on pagination
        
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
            'code' => [
                'required', 
                'string', 
                'max:10', 
                'unique:subjects', 
                'regex:/^[A-Z0-9]+$/'
            ],
            'description' => ['nullable', 'string'],
            'teachers' => ['nullable', 'array'],
            'teachers.*' => ['exists:users,id,role_id,2'], // Ensure we only attach teacher users
        ], [
            'code.regex' => 'The code format is invalid. Use uppercase letters and numbers only.',
            'teachers.*.exists' => 'One or more selected teachers are invalid.'
        ]);
        
        try {
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
                ->with('success', 'Mata pelajaran berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat mata pelajaran: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified subject details
     */
    public function show(Subject $subject)
    {
        $subject->load(['teachers', 'classrooms', 'assignments', 'materials']);
        
        // Get students count for each classroom
        $classroomStudentCounts = [];
        foreach ($subject->classrooms as $classroom) {
            $classroomStudentCounts[$classroom->id] = User::where('classroom_id', $classroom->id)
                ->whereHas('role', function($q) {
                    $q->where('slug', 'siswa');
                })->count();
        }
        
        return view('admin.subjects.show', compact('subject', 'classroomStudentCounts'));
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
            'code' => [
                'required', 
                'string', 
                'max:10', 
                Rule::unique('subjects')->ignore($subject->id),
                'regex:/^[A-Z0-9]+$/'
            ],
            'description' => ['nullable', 'string'],
            'teachers' => ['nullable', 'array'],
            'teachers.*' => ['exists:users,id'],
        ], [
            'code.regex' => 'The code format is invalid. Use uppercase letters and numbers only.',
            'teachers.*.exists' => 'One or more selected teachers are invalid.'
        ]);
        
        try {
            $subject->update([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'description' => $validated['description'] ?? null,
            ]);
            
            // Sync teachers
            $subject->teachers()->sync($validated['teachers'] ?? []);
            
            return redirect()->route('admin.subjects.index')
                ->with('success', 'Mata pelajaran berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui mata pelajaran: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified subject
     */
    public function destroy(Subject $subject)
    {
        try {
            // Check for related data that might cause issues
            $assignmentsCount = $subject->assignments()->count();
            $materialsCount = $subject->materials()->count();
            
            if ($assignmentsCount > 0 || $materialsCount > 0) {
                $message = 'Mata pelajaran tidak dapat dihapus karena masih memiliki ';
                $relatedData = [];
                
                if ($assignmentsCount > 0) {
                    $relatedData[] = $assignmentsCount . ' tugas';
                }
                
                if ($materialsCount > 0) {
                    $relatedData[] = $materialsCount . ' materi';
                }
                
                $message .= implode(' dan ', $relatedData) . ' terkait.';
                
                return redirect()->route('admin.subjects.index')
                    ->with('error', $message);
            }
            
            // Detach all teachers
            $subject->teachers()->detach();
            
            // Detach all classrooms
            $subject->classrooms()->detach();
            
            $subject->delete();
            
            return redirect()->route('admin.subjects.index')
                ->with('success', 'Mata pelajaran berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.subjects.index')
                ->with('error', 'Terjadi kesalahan saat menghapus mata pelajaran: ' . $e->getMessage());
        }
    }
}
