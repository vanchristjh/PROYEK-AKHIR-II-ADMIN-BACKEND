<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')->paginate(10);
        return view('dashboard.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new student.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            // First try to get classes from the class_rooms table
            if (Schema::hasTable('class_rooms')) {
                $classes = ClassRoom::orderBy('level')->orderBy('name')->get();
            } else if (Schema::hasTable('classes')) {
                // If class_rooms doesn't exist, try the 'classes' table as fallback
                $classes = DB::table('classes')->orderBy('level')->orderBy('name')->get();
            } else {
                $classes = collect([]);
            }
        } catch (QueryException $e) {
            // If there's a database error, provide an empty collection
            $classes = collect([]);
        }
        
        return view('dashboard.students.create', compact('classes'));
    }

    /**
     * Store a newly created student in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'nis' => 'required|string|max:20|unique:users',
            'gender' => 'required|in:L,P,laki-laki,perempuan',
            'phone' => 'nullable|string|max:15',  // Keep this name for the form field
            'address' => 'nullable|string',
            'class_id' => 'required|exists:class_rooms,id',
        ], [
            'class_id.required' => 'The class field is required.',
            'class_id.exists' => 'The selected class is invalid.',
            'gender.in' => 'The selected gender is invalid. Please choose either Laki-laki or Perempuan.',
        ]);
        
        try {
            // Create the student user with correct field mappings
            $student = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nis' => $request->nis,
                'gender' => $request->gender, // The model will normalize this
                'phone_number' => $request->phone ?? null,  // Map 'phone' form field to 'phone_number' database column
                'address' => $request->address,
                'role' => 'student',
                'class_id' => $request->class_id,
            ]);
            
            return redirect()->route('students.index')
                ->with('success', 'Student created successfully.');
        } catch (QueryException $e) {
            // Provide more detailed error message for debugging
            return back()->withInput()->withErrors([
                'error' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    public function edit(User $student)
    {
        return view('dashboard.students.edit', compact('student'));
    }

    public function update(Request $request, User $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$student->id,
            'nis' => 'required|string|unique:users,nis,'.$student->id,
            'nisn' => 'nullable|string|unique:users,nisn,'.$student->id,
            'class' => 'required|string',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'academic_year' => 'nullable|string',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:15',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'class' => $request->class,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'academic_year' => $request->academic_year,
            'parent_name' => $request->parent_name,
            'parent_phone' => $request->parent_phone,
        ];

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($student->profile_photo) {
                Storage::disk('public')->delete($student->profile_photo);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $userData['profile_photo'] = $path;
        }

        $student->update($userData);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            $student->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('students.index')
            ->with('success', 'Akun siswa berhasil diperbarui.');
    }

    public function destroy(User $student)
    {
        // Delete profile photo if exists
        if ($student->profile_photo) {
            Storage::disk('public')->delete($student->profile_photo);
        }
        
        $student->delete();
        
        return redirect()->route('students.index')
            ->with('success', 'Akun siswa berhasil dihapus.');
    }
}