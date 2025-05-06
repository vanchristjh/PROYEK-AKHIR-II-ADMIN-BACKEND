<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class MaterialController extends Controller
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
        
        $materials = $query->latest()->paginate(10);
        
        // Get subjects for filtering
        $subjects = Material::where('classroom_id', $classroomId)
            ->where('publish_date', '<=', now())
            ->with('subject')
            ->get()
            ->pluck('subject')
            ->unique('id')
            ->sortBy('name');
            
        return view('siswa.materials.index', compact('materials', 'subjects', 'subjectId'));
    }
    
    /**
     * Show details of a specific material
     */
    public function show(Material $material)
    {
        // Verify this material is for student's classroom and published
        if ($material->classroom_id !== Auth::user()->classroom_id || $material->publish_date > now()) {
            abort(403, 'Unauthorized action.');
        }
        
        $material->load('subject', 'teacher');
        
        return view('siswa.materials.show', compact('material'));
    }
    
    /**
     * Download a material file
     */
    public function download(Material $material)
    {
        // Verify this material is for student's classroom and published
        if ($material->classroom_id !== Auth::user()->classroom_id || $material->publish_date > now()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Get original filename from path
        $filename = basename($material->file_path);
        
        // Return file download
        return Storage::disk('public')->download($material->file_path, $filename);
    }
}
