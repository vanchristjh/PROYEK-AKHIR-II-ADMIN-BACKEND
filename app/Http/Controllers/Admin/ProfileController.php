<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\ActivityLog;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = Auth::user();
        $recentActivities = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.profile.show', compact('user', 'recentActivities'));
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        $user = auth()->user();
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:2048'], // 2MB Max
            'id_number' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'current_password' => ['nullable', 'required_with:password', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);
        
        // Update basic profile info
        $user->name = $request->name;
        $user->email = $request->email;
        $user->id_number = $request->id_number;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->bio = $request->bio;
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }
        
        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            
            // Log activity
            ActivityLog::log(
                'password_changed',
                'Password berhasil diperbarui'
            );
        }
        
        $user->save();
        
        // Log activity
        ActivityLog::log(
            'profile_updated',
            'Profil berhasil diperbarui'
        );
        
        return redirect()->route('admin.profile.show')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
