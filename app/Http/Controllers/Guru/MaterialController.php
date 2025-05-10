<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $query = Material::where('teacher_id', $teacher->id);
        
        // Filter by subject if provided
        if ($request->has('subject') && !empty($request->subject)) {
            $query->where('subject_id', $request->subject);
        }
        
        $materials = $query->latest()->paginate(12);
        
        return view('guru.materials.index', [
            'materials' => $materials
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teacher = Auth::user();
        $subjects = $teacher->teacherSubjects;
        $classrooms = Classroom::all();
        
        return view('guru.materials.create', [
            'subjects' => $subjects,
            'classrooms' => $classrooms,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|array',
            'classroom_id.*' => 'exists:classrooms,id',
            'material_file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,png,mp4,zip,rar|max:20480', // 20MB max
        ]);

        try {
            // Handle file upload
            $filePath = null;
            if ($request->hasFile('material_file') && $request->file('material_file')->isValid()) {
                $file = $request->file('material_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                // Store file in public storage for easy access
                $filePath = $file->storeAs('materials', $fileName, 'public');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['material_file' => 'File tidak valid atau tidak dapat diunggah.']);
            }

            // Create material record
            $material = Material::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'subject_id' => $validated['subject_id'],
                'teacher_id' => auth()->id(),
                'file_path' => $filePath, // Make sure this field exists in your database
                'publish_date' => now(),
            ]);

            // Associate with classrooms
            $material->classrooms()->attach($validated['classroom_id']);

            return redirect()->route('guru.materials.index')
                ->with('success', 'Materi berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error creating material: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
        // Ensure the teacher owns this material
        if ($material->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('guru.materials.show', [
            'material' => $material
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function edit(Material $material)
    {
        // Ensure the teacher owns this material
        if ($material->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $teacher = Auth::user();
        $subjects = $teacher->teacherSubjects;
        $classrooms = Classroom::all();
        
        return view('guru.materials.edit', [
            'material' => $material,
            'subjects' => $subjects,
            'classrooms' => $classrooms,
            'selectedClassrooms' => $material->classrooms->pluck('id')->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Material $material)
    {
        // Ensure the teacher owns this material
        if ($material->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|array',
            'classroom_id.*' => 'exists:classrooms,id',
            'material_file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,png,mp4,zip,rar|max:20480', // 20MB max
        ]);

        try {
            // Handle file upload if present
            if ($request->hasFile('material_file') && $request->file('material_file')->isValid()) {
                // Delete old file if exists
                if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                    Storage::disk('public')->delete($material->file_path);
                }
                
                // Upload new file
                $file = $request->file('material_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('materials', $fileName, 'public');
                
                // Update file path
                $material->file_path = $filePath;
            }

            // Update material details
            $material->title = $validated['title'];
            $material->description = $validated['description'];
            $material->subject_id = $validated['subject_id'];
            $material->save();

            // Update classroom relationships
            $material->classrooms()->sync($validated['classroom_id']);

            return redirect()->route('guru.materials.show', $material)
                ->with('success', 'Materi berhasil diperbarui.');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error updating material: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {
        // Ensure the teacher owns this material
        if ($material->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Delete file if exists
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }
        
        // Delete material record and its relationships
        $material->classrooms()->detach();
        $material->delete();
        
        return redirect()->route('guru.materials.index')
            ->with('success', 'Materi berhasil dihapus.');
    }
}
