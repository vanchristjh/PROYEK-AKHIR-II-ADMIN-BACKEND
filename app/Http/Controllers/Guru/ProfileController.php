<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Ensure a teacher record exists for the given user.
     *
     * @param \App\Models\User $user
     * @return \App\Models\Teacher
     */
    private function ensureTeacherRecord($user)
    {
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        // If no teacher record exists, create one
        if (!$teacher && $user->isGuru()) {
            $teacher = new Teacher();
            $teacher->user_id = $user->id;
            $teacher->nip = $user->nip ?? '';
            $teacher->phone = $user->phone ?? '';
            $teacher->address = $user->address ?? '';
            $teacher->save();
        }
        
        return $teacher;
    }

    /**
     * Display the user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Auth::user();
        $teacher = $this->ensureTeacherRecord($user);
        
        // Get today's schedules for the teacher
        $today = now()->format('l');
        $indonesianDays = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        
        $todayIndonesian = $indonesianDays[$today] ?? '';
        
        // Get schedules for today - try both teacher ID and user ID
        $todaySchedules = \App\Models\Schedule::with(['subject', 'classroom'])
            ->where(function($query) use ($teacher, $user) {
                $query->where('teacher_id', $teacher ? $teacher->id : 0)
                      ->orWhere('teacher_id', $user->id);
            })
            ->where('day', $todayIndonesian)
            ->orderBy('start_time')
            ->get();
        
        return view('guru.profile.show_new', compact('user', 'todaySchedules'));
    }

    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = auth()->user();
        return view('guru.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'avatar' => ['nullable', 'image', 'max:1024'],
        ]);
        
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }
        
        $user->update($validated);
        
        return redirect()->route('guru.profile.show')->with('status', 'profile-updated');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);
        
        $user = auth()->user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        return redirect()->route('guru.profile.show')->with('status', 'password-updated');
    }
}
