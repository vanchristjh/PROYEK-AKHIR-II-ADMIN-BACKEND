<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class SiswaMaterialController extends Controller
{
    /**
     * Display all materials available to the student
     */
    public function index(Request $request)
    {
        // Get student's classroom
        $classroomId = Auth::user()->classroom_id;
        
        if (!$classroomId) {
            // Return empty paginator instead of array
            $emptyPaginator = new LengthAwarePaginator([], 0, 10);
            $emptyPaginator->withPath(request()->url());
            
            return view('siswa.materials.index', [
                'materials' => $emptyPaginator,
                'subjects' => [],
                'message' => 'You are not assigned to any classroom yet.'
            ]);
        }
        
        // Filter by subject if provided
        $subjectId = $request->input('subject_id');
        
        $query = Material::where('classroom_id', $classroomId)
            ->where('publish_date', '<=', now()) // Only published materials
            ->with('subject', 'teacher');
            
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }
        
        $materials = $query->latest('publish_date')->paginate(10);
        
        // Get subjects for filter dropdown
        $subjects = Subject::whereHas('classrooms', function($query) use ($classroomId) {
            $query->where('classrooms.id', $classroomId);
        })->get();
        
        return view('siswa.materials.index', compact('materials', 'subjects'));
    }
    
    /**
     * Display details of a specific material
     */
    public function show(Material $material)
    {
        // Verify material is for student's classroom
        if ($material->classroom_id !== Auth::user()->classroom_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Verify material is published
        if ($material->publish_date > now()) {
            abort(403, 'This material is not available yet.');
        }
        
        return view('siswa.materials.show', compact('material'));
    }
    
    /**
     * Download a material file
     */
    public function download(Material $material)
    {
        // Verify material is for student's classroom
        if ($material->classroom_id !== Auth::user()->classroom_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Verify material is published
        if ($material->publish_date > now()) {
            abort(403, 'This material is not available yet.');
        }
        
        if (!Storage::exists($material->file_path)) {
            abort(404, 'File not found.');
        }
        
        return Storage::download($material->file_path, $material->file_name);
    }
}
