<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Add debug logging to help troubleshoot
        \Log::info('Login attempt for username: ' . $credentials['username']);
        
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            \Log::info('Login successful for user: ' . Auth::user()->username);
            
            // Check if the role relationship exists
            $user = Auth::user();
            if (!$user->role) {
                \Log::error('User has no role: ' . $user->id);
                Auth::logout();
                $request->session()->invalidate();
                return back()->withErrors(['username' => 'Account has no role assigned. Please contact administrator.']);
            }
            
            // Redirect based on user role
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->isGuru()) {
                return redirect()->intended(route('guru.dashboard'));
            } else {
                return redirect()->intended(route('siswa.dashboard'));
            }
        }

        \Log::warning('Failed login attempt for username: ' . $credentials['username']);
        
        throw ValidationException::withMessages([
            'username' => ['Username atau password yang Anda masukkan tidak valid.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
