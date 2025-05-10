<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SiswaSettingsController extends Controller
{
    /**
     * Display the settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        return view('siswa.settings.index', compact('user'));
    }

    /**
     * Update the user's settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'email' => 'required|email|unique:users,email,'.$user->id,
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
            'theme' => 'nullable|string',
            'notifications_enabled' => 'nullable|boolean',
        ]);

        $user->email = $request->email;
        
        // Only update password if provided
        if ($request->filled('current_password') && $request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            
            $user->password = Hash::make($request->password);
        }
        
        // Update preferences if they exist
        if ($request->has('theme')) {
            $user->preferences = array_merge($user->preferences ?? [], ['theme' => $request->theme]);
        }
        
        if ($request->has('notifications_enabled')) {
            $user->preferences = array_merge($user->preferences ?? [], ['notifications_enabled' => (bool) $request->notifications_enabled]);
        }
        
        $user->save();
        
        return redirect()->route('siswa.settings.index')->with('success', 'Settings updated successfully.');
    }
}
