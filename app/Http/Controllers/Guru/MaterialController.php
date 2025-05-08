<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    /**
     * Display a listing of materials
     */
    public function index()
    {
        $teacher = Auth::user();
        $materials = Material::with(['subject'])
            ->whereHas('subject.teachers', function($query) use ($teacher) {
                $query->where('user_id', $teacher->id);
            })
            ->latest()
            ->paginate(10);
        
        return view('guru.materials.index', compact('materials'));
    }

    /**
     * Show form to create a new material
     */
    public function create()
    {
        $teacher = Auth::user();
        $subjects = $teacher->teacherSubjects;
        $classrooms = Classroom::all();
        
        return view('guru.materials.create', compact('subjects', 'classrooms'));
    }

    /**
     * Store a newly created material
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|array', // Validate that classroom_id is provided as an array
            'classroom_id.*' => 'exists:classrooms,id', // Validate that each classroom_id exists
            'attachment' => 'nullable|file|max:20480', // 20MB max
        ]);
        
        // Extract classroom IDs from the validated data
        $classroomIds = $validated['classroom_id'];
        
        // Use the first classroom ID as the primary classroom_id field
        // This is needed because the materials table has a non-nullable classroom_id field
        $validated['classroom_id'] = $classroomIds[0] ?? null;
        
        // Initialize file_path to null by default
        $validated['file_path'] = null;
        
        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('materials', 'public');
            $validated['file_path'] = $filePath;
        }
        
        $validated['teacher_id'] = Auth::id();
        $validated['publish_date'] = now(); // Set default publish date to now
        
        // Create the material with the first classroom_id
        $material = Material::create($validated);
        
        // Attach all selected classrooms to the material (many-to-many relationship)
        if (!empty($classroomIds)) {
            $material->classrooms()->attach($classroomIds);
        }
        
        return redirect()->route('guru.materials.index')
            ->with('success', 'Materi berhasil dibuat!');
    }

    /**
     * Display the specified material
     */
    public function show(Material $material)
    {
        return view('guru.materials.show', compact('material'));
    }

    /**
     * Show form to edit a material
     */
    public function edit(Material $material)
    {
        $teacher = Auth::user();
        $subjects = $teacher->teacherSubjects;
        $classrooms = Classroom::all();
        $selectedClassrooms = $material->classrooms->pluck('id')->toArray();
        
        return view('guru.materials.edit', compact('material', 'subjects', 'classrooms', 'selectedClassrooms'));
    }

    /**
     * Update the specified material
     */
    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|array',
            'classroom_id.*' => 'exists:classrooms,id',
            'attachment' => 'nullable|file|max:20480', // 20MB max
        ]);
        
        // Extract classroom IDs
        $classroomIds = $validated['classroom_id'];
        
        // Use the first classroom ID as the primary classroom_id field
        $validated['classroom_id'] = $classroomIds[0] ?? null;
        
        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($material->file_path) {
                Storage::disk('public')->delete($material->file_path);
            }
            
            $filePath = $request->file('attachment')->store('materials', 'public');
            $validated['file_path'] = $filePath;
        }
        
        $material->update($validated);
        
        // Sync classrooms for many-to-many relationship
        if (!empty($classroomIds)) {
            $material->classrooms()->sync($classroomIds);
        } else {
            $material->classrooms()->detach();
        }
        
        return redirect()->route('guru.materials.index')
            ->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Remove the specified material
     */
    public function destroy(Material $material)
    {
        // Delete file if exists
        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }
        
        // Remove classroom associations
        $material->classrooms()->detach();
        
        $material->delete();
        
        return redirect()->route('guru.materials.index')
            ->with('success', 'Materi berhasil dihapus!');
    }
}
