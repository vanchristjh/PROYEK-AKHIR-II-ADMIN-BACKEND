<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class ClassroomController extends Controller
{
    public function index(Request $request)
    {
        $query = Classroom::with('homeroomTeacher', 'students', 'subjects');

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('grade_level') && $request->grade_level != '') {
            $query->where('grade_level', $request->grade_level);
        }

        $classrooms = $query->latest()->paginate(10);
        
        return view('admin.classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        $teacherRole = Role::where('name', 'guru')->orWhere('slug', 'guru')->first();
        $teachers = User::where('role_id', $teacherRole->id ?? 0)->get();
        $subjects = Subject::all();

        return view('admin.classrooms.create', compact('teachers', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:classrooms',
            'grade_level' => 'required|integer|min:10|max:12',
            'academic_year' => 'required|string',
            'homeroom_teacher_id' => 'required|exists:users,id',
            'capacity' => 'required|integer|min:1',
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $classroom = Classroom::create([
            'name' => $request->name,
            'grade_level' => $request->grade_level,
            'academic_year' => $request->academic_year,
            'homeroom_teacher_id' => $request->homeroom_teacher_id,
            'capacity' => $request->capacity,
        ]);

        $classroom->subjects()->attach($request->subjects);

        return redirect()->route('admin.classrooms.index')->with('success', 'Classroom created successfully');
    }

    public function show(Classroom $classroom)
    {
        $classroom->load('homeroomTeacher', 'students', 'subjects');
        return view('admin.classrooms.show', compact('classroom'));
    }

    public function edit(Classroom $classroom)
    {
        $teacherRole = Role::where('name', 'guru')->orWhere('slug', 'guru')->first();
        $teachers = User::where('role_id', $teacherRole->id ?? 0)->get();
        $subjects = Subject::all();
        
        return view('admin.classrooms.edit', compact('classroom', 'teachers', 'subjects'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classrooms,name,' . $classroom->id,
            'grade_level' => 'required|integer|in:10,11,12',
            'academic_year' => 'required|string|max:10',
            'homeroom_teacher_id' => 'required|exists:users,id',
            'capacity' => 'required|integer|min:1',
            'room_number' => 'nullable|string|max:50',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $classroom->update([
            'name' => $validated['name'],
            'grade_level' => $validated['grade_level'],
            'academic_year' => $validated['academic_year'],
            'homeroom_teacher_id' => $validated['homeroom_teacher_id'],
            'capacity' => $validated['capacity'],
            'room_number' => $validated['room_number'] ?? null,
        ]);

        // Sync subjects
        if ($request->has('subjects')) {
            $classroom->subjects()->sync($request->subjects);
        } else {
            $classroom->subjects()->detach();
        }

        return redirect()->route('admin.classrooms.show', $classroom)
            ->with('success', 'Informasi kelas berhasil diperbarui.');
    }

    public function destroy(Classroom $classroom)
    {
        // Detach relationships first
        $classroom->subjects()->detach();
        
        // Update students who are in this classroom to have no classroom
        User::where('classroom_id', $classroom->id)->update(['classroom_id' => null]);
        
        $classroom->delete();

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    public function removeStudent(Classroom $classroom, User $student)
    {
        // Check if the user is actually a student in this classroom
        if ($student->classroom_id == $classroom->id) {
            $student->update(['classroom_id' => null]);
            return redirect()->route('admin.classrooms.show', $classroom)
                ->with('success', 'Siswa berhasil dikeluarkan dari kelas.');
        }
        
        return redirect()->route('admin.classrooms.show', $classroom)
            ->with('error', 'Siswa tidak terdaftar dalam kelas ini.');
    }

    public function export(Classroom $classroom)
    {
        $classroom->load('homeroomTeacher', 'students', 'subjects');
        
        $pdf = PDF::loadView('admin.classrooms.export', ['classroom' => $classroom]);
        return $pdf->download('kelas-' . $classroom->name . '.pdf');
    }
    
    public function exportAll()
    {
        $classrooms = Classroom::with('homeroomTeacher', 'students', 'subjects')->get();
        
        $pdf = PDF::loadView('admin.classrooms.export-all', ['classrooms' => $classrooms]);
        return $pdf->download('data-semua-kelas.pdf');
    }
}
