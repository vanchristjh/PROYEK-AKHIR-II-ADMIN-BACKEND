<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaMaterialController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Check if the user has a student record
        if (!$user->student) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Profil siswa tidak ditemukan. Silahkan hubungi administrator.');
        }
        
        // Check if the student is assigned to a classroom
        $classroom = $user->student->classroom;
        if (!$classroom) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Anda belum terdaftar di kelas manapun. Silahkan hubungi administrator.');
        }
        
        // Get subjects for the student's classroom
        $subjects = Subject::whereHas('classrooms', function($query) use ($classroom) {
            $query->where('classrooms.id', $classroom->id);
        })->get();
        
        // Get the selected subject ID from the request
        $subjectId = $request->subject_id;
        
        // Query materials based on subject filter
        $materialsQuery = Material::query();
        
        if ($subjectId) {
            // Filter by selected subject
            $materialsQuery->where('subject_id', $subjectId);
        } else {
            // Show materials for all subjects in the student's classroom
            $materialsQuery->whereIn('subject_id', $subjects->pluck('id'));
        }
        
        // Get materials that are assigned to the student's classroom
        $materialsQuery->whereHas('classrooms', function($query) use ($classroom) {
            $query->where('classrooms.id', $classroom->id);
        });
        
        $materials = $materialsQuery->latest()->paginate(10);
        
        return view('siswa.materials.index', compact('materials', 'subjects', 'classroom'));
    }
    
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
