<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Get all students
     */
    public function index()
    {
        // Get all users with the 'student' role
        $students = User::where('role', 'student')->get();
        
        return response()->json([
            'students' => $students,
            'count' => $students->count(),
        ]);
    }
    
    /**
     * Get a specific student
     */
    public function show($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        
        return response()->json([
            'student' => $student,
        ]);
    }
    
    /**
     * Update a student's profile
     */
    public function update(Request $request, $id)
    {
        // Validate student ID
        $student = User::where('role', 'student')->findOrFail($id);
        
        // Check if the authenticated user has permission
        if (Auth::id() != $id && Auth::user()->role != 'admin') {
            return response()->json([
                'message' => 'Anda tidak memiliki akses untuk memperbarui profil ini',
            ], 403);
        }
        
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($id),
            ],
            'nisn' => 'sometimes|string|max:20',
            'class_name' => 'sometimes|string|max:20',
            'phone_number' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:255',
            'gender' => 'sometimes|string|in:male,female',
            'date_of_birth' => 'sometimes|date',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        // Update student data
        $student->update($request->only([
            'name', 'email', 'nisn', 'class_name', 'phone_number', 
            'address', 'gender', 'date_of_birth'
        ]));
        
        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'student' => $student,
        ]);
    }
    
    /**
     * Upload student profile photo
     */
    public function uploadProfilePhoto(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $id = $request->input('id');
        $student = User::where('role', 'student')->findOrFail($id);
        
        // Check if the authenticated user has permission
        if (Auth::id() != $id && Auth::user()->role != 'admin') {
            return response()->json([
                'message' => 'Anda tidak memiliki akses untuk memperbarui foto profil ini',
            ], 403);
        }
        
        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $filename = time() . '_' . $student->id . '.' . $photo->getClientOriginalExtension();
            
            // Store the photo
            $path = $photo->storeAs('profile-photos', $filename, 'public');
            
            // Update the student's profile photo path
            $student->profile_photo_path = $path;
            $student->save();
            
            return response()->json([
                'message' => 'Foto profil berhasil diunggah',
                'profile_photo_url' => $student->profile_photo_url,
            ]);
        }
        
        return response()->json([
            'message' => 'Tidak ada file yang diunggah',
        ], 400);
    }
} 