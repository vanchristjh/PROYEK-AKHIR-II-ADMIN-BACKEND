<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ImagesHelper;
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
            'password' => 'required|string|min:8|confirmed',
            'nis' => 'required|string|max:20|unique:users',
            'nisn' => 'nullable|string|unique:users',
            'class_id' => 'required|exists:class_rooms,id',
            'gender' => 'required|in:L,P',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'academic_year' => 'nullable|string',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:15',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024', // Max 1MB
        ]);
        
        try {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'class_id' => $request->class_id,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'academic_year' => $request->academic_year,
                'parent_name' => $request->parent_name,
                'parent_phone' => $request->parent_phone,
                'role' => 'student',
            ];

            // Handle profile photo upload with processing
            if ($request->hasFile('profile_photo')) {
                try {
                    // Use our custom image processing helper
                    $photoPath = ImagesHelper::processImage(
                        $request->file('profile_photo'),
                        'profile-photos',
                        300, // max width
                        300, // max height
                        80   // quality
                    );
                    $userData['profile_photo'] = $photoPath;
                } catch (\Exception $e) {
                    // Fallback to simple upload if image processing fails
                    $path = $request->file('profile_photo')->store('profile-photos', 'public');
                    $userData['profile_photo'] = $path;
                }
            }
            
            $student = User::create($userData);
            
            return redirect()->route('students.index')
                ->with('success', 'Akun siswa berhasil dibuat.');
                   
        } catch (QueryException $e) {
            return back()->withInput()->withErrors([
                'error' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    public function edit(User $student)
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
        
        return view('dashboard.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, User $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$student->id,
            'nis' => 'required|string|unique:users,nis,'.$student->id,
            'nisn' => 'nullable|string|unique:users,nisn,'.$student->id,
            'class_id' => 'required|exists:class_rooms,id',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'academic_year' => 'nullable|string',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:15',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024', // Max 1MB
        ]);
        
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'class_id' => $request->class_id,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'academic_year' => $request->academic_year,
            'parent_name' => $request->parent_name,
            'parent_phone' => $request->parent_phone,
        ];

        try {
            // Handle profile photo update
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($student->profile_photo) {
                    Storage::disk('public')->delete($student->profile_photo);
                }
                
                try {
                    // Use our custom image processing helper
                    $photoPath = ImagesHelper::processImage(
                        $request->file('profile_photo'),
                        'profile-photos',
                        300, // max width
                        300, // max height
                        80   // quality
                    );
                    $userData['profile_photo'] = $photoPath;
                } catch (\Exception $e) {
                    // Fallback to simple upload if image processing fails
                    $path = $request->file('profile_photo')->store('profile-photos', 'public');
                    $userData['profile_photo'] = $path;
                }
            }
            
            $student->update($userData);
            
            // Update password if provided
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
                
        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
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

    /**
     * Find a student by ID
     * 
     * @param int $id
     * @return \App\Models\User
     */
    public function find($id)
    {
        // Use the User model with role='student' filter instead of Student model directly
        return User::where('role', 'student')->findOrFail($id);
    }
    
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        return view('dashboard.students.show', compact('student'));
    }
}