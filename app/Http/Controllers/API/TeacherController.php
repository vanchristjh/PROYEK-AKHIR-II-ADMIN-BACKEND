<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * Get all teachers
     */
    public function index()
    {
        // Get all users with the 'teacher' role
        $teachers = User::where('role', 'teacher')->get();
        
        return response()->json([
            'teachers' => $teachers,
            'count' => $teachers->count(),
        ]);
    }
    
    /**
     * Get a specific teacher
     */
    public function show($id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        
        return response()->json([
            'teacher' => $teacher,
        ]);
    }
    
    /**
     * Update a teacher's profile
     */
    public function update(Request $request, $id)
    {
        // Validate teacher ID
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        
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
            'nip' => 'sometimes|string|max:20',
            'subject' => 'sometimes|string|max:100',
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
        
        // Update teacher data
        $teacher->update($request->only([
            'name', 'email', 'nip', 'subject', 'phone_number', 
            'address', 'gender', 'date_of_birth'
        ]));
        
        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'teacher' => $teacher,
        ]);
    }
    
    /**
     * Upload teacher profile photo
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
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        
        // Check if the authenticated user has permission
        if (Auth::id() != $id && Auth::user()->role != 'admin') {
            return response()->json([
                'message' => 'Anda tidak memiliki akses untuk memperbarui foto profil ini',
            ], 403);
        }
        
        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $filename = time() . '_' . $teacher->id . '.' . $photo->getClientOriginalExtension();
            
            // Store the photo
            $path = $photo->storeAs('profile-photos', $filename, 'public');
            
            // Update the teacher's profile photo path
            $teacher->profile_photo_path = $path;
            $teacher->save();
            
            return response()->json([
                'message' => 'Foto profil berhasil diunggah',
                'profile_photo_url' => $teacher->profile_photo_url,
            ]);
        }
        
        return response()->json([
            'message' => 'Tidak ada file yang diunggah',
        ], 400);
    }
} 