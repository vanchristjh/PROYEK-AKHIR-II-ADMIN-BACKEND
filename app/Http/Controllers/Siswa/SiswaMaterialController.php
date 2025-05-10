<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Subject;
use App\Models\Classroom;

class SiswaMaterialController extends Controller
{
    /**
     * Display a listing of materials for students.
     */
    public function index(Request $request)
    {
        $student = auth()->user();
        $classroom = $student->classroom;
        
        if (!$classroom) {
            return view('siswa.materials.index', [
                'materials' => collect([]), // Return empty collection that can be paginated
                'subjects' => collect([]),
            ]);
        }
        
        // Get subjects for the student's classroom
        $subjects = Subject::whereHas('classrooms', function($query) use ($classroom) {
            $query->where('classrooms.id', $classroom->id);
        })->get();
        
        // Base query for materials
        $query = Material::whereHas('subjects', function($query) use ($classroom) {
            $query->whereHas('classrooms', function($q) use ($classroom) {
                $q->where('classrooms.id', $classroom->id);
            });
        })->with(['teacher', 'subjects']);
        
        // Filter by subject if provided
        if ($request->filled('subject')) {
            $query->whereHas('subjects', function($q) use ($request) {
                $q->where('subjects.id', $request->subject);
            });
        }
        
        // Filter by search term if provided
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }
        
        // Sort materials
        $query->orderBy('created_at', 'desc');
        
        // Paginate the results - this is key to fixing the error
        $materials = $query->paginate(10);
        
        return view('siswa.materials.index', compact('materials', 'subjects'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = auth()->user();
        $material = Material::with(['subject', 'teacher'])->findOrFail($id);
        
        // Check if the student has access to this material through their classroom
        $hasAccess = $material->classrooms()->where('classrooms.id', $student->classroom_id)->exists();
        
        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }
        
        return view('siswa.materials.show', [
            'material' => $material
        ]);
    }
}
