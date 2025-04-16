<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $classes = ClassRoom::all();
        $subjects = Subject::all();
        $selectedClass = null;
        $students = collect();
        
        if ($request->has('class_id') && $request->class_id) {
            $selectedClass = ClassRoom::find($request->class_id);
            $students = User::where('role', 'student')
                          ->where('class_id', $request->class_id)
                          ->get();
        }
        
        return view('dashboard.grades.index', compact('classes', 'subjects', 'selectedClass', 'students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = ClassRoom::all();
        $subjects = Subject::all();
        $students = collect();
        
        return view('dashboard.grades.create', compact('classes', 'subjects', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:class_rooms,id',
            'academic_year' => 'required|string',
            'semester' => 'required|in:1,2',
            'assignment_score' => 'nullable|numeric|min:0|max:100',
            'mid_exam_score' => 'nullable|numeric|min:0|max:100',
            'final_exam_score' => 'nullable|numeric|min:0|max:100',
        ]);
        
        // Calculate final score (example: 30% assignment, 30% mid exam, 40% final exam)
        $finalScore = ($validated['assignment_score'] * 0.3) + 
                      ($validated['mid_exam_score'] * 0.3) + 
                      ($validated['final_exam_score'] * 0.4);
        
        // Determine grade based on final score
        $grade = $this->calculateGrade($finalScore);
        
        Grade::create(array_merge($validated, [
            'final_score' => $finalScore,
            'grade' => $grade,
        ]));
        
        return redirect()->route('grades.index')
            ->with('success', 'Data nilai berhasil ditambahkan');
    }

    /**
     * Calculate letter grade based on numeric score
     */
    private function calculateGrade($score)
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'E';
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        return view('dashboard.grades.show', compact('grade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        $classes = ClassRoom::all();
        $subjects = Subject::all();
        $students = User::where('class_id', $grade->class_id)->get();
        
        return view('dashboard.grades.edit', compact('grade', 'classes', 'subjects', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:class_rooms,id',
            'academic_year' => 'required|string',
            'semester' => 'required|in:1,2',
            'assignment_score' => 'nullable|numeric|min:0|max:100',
            'mid_exam_score' => 'nullable|numeric|min:0|max:100',
            'final_exam_score' => 'nullable|numeric|min:0|max:100',
        ]);
        
        // Calculate final score
        $finalScore = ($validated['assignment_score'] * 0.3) + 
                      ($validated['mid_exam_score'] * 0.3) + 
                      ($validated['final_exam_score'] * 0.4);
        
        // Determine grade
        $grade->update(array_merge($validated, [
            'final_score' => $finalScore,
            'grade' => $this->calculateGrade($finalScore),
        ]));
        
        return redirect()->route('grades.index')
            ->with('success', 'Data nilai berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();
        
        return redirect()->route('grades.index')
            ->with('success', 'Data nilai berhasil dihapus');
    }
}
