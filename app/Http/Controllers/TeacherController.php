<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')->paginate(10);
        return view('dashboard.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('dashboard.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nip' => 'required|string|unique:users',
            'nuptk' => 'nullable|string|unique:users',
            'subject' => 'required|string',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'position' => 'nullable|string',
            'join_date' => 'nullable|date',
            'education_level' => 'nullable|string',
            'education_institution' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'nip' => $request->nip,
            'nuptk' => $request->nuptk,
            'subject' => $request->subject,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'position' => $request->position,
            'join_date' => $request->join_date,
            'education_level' => $request->education_level,
            'education_institution' => $request->education_institution,
        ];

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $userData['profile_photo'] = $path;
        }

        User::create($userData);

        return redirect()->route('teachers.index')
            ->with('success', 'Akun guru berhasil dibuat.');
    }

    public function edit(User $teacher)
    {
        return view('dashboard.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, User $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$teacher->id,
            'nip' => 'required|string|unique:users,nip,'.$teacher->id,
            'nuptk' => 'nullable|string|unique:users,nuptk,'.$teacher->id,
            'subject' => 'required|string',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'position' => 'nullable|string',
            'join_date' => 'nullable|date',
            'education_level' => 'nullable|string',
            'education_institution' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'nuptk' => $request->nuptk,
            'subject' => $request->subject,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'position' => $request->position,
            'join_date' => $request->join_date,
            'education_level' => $request->education_level,
            'education_institution' => $request->education_institution,
        ];

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($teacher->profile_photo) {
                Storage::disk('public')->delete($teacher->profile_photo);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $userData['profile_photo'] = $path;
        }

        $teacher->update($userData);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            $teacher->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('teachers.index')
            ->with('success', 'Akun guru berhasil diperbarui.');
    }

    public function destroy(User $teacher)
    {
        // Delete profile photo if exists
        if ($teacher->profile_photo) {
            Storage::disk('public')->delete($teacher->profile_photo);
        }
        
        $teacher->delete();
        
        return redirect()->route('teachers.index')
            ->with('success', 'Akun guru berhasil dihapus.');
    }
} 