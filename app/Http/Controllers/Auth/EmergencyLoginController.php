<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class EmergencyLoginController extends Controller
{
    /**
     * Show the failsafe login form.
     * 
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.emergency_login');
    }
    
    /**
     * Handle a login request to the application with minimal redirections.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        
        Log::info('Emergency login attempt for username: ' . $credentials['username']);
        
        // Clear any session data that might cause redirects
        Session::flush();
        
        if (Auth::attempt($credentials, false)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            Log::info('Emergency login successful for user: ' . $user->username);
            
            // Check role and render appropriate view directly
            if ($user->role && $user->role->slug === 'admin') {
                return view('admin.dashboard.debug', [
                    'user' => $user,
                    'debug_info' => [
                        'method' => 'emergency_login',
                        'message' => 'Logged in through emergency controller'
                    ]
                ]);
            } elseif ($user->role && $user->role->slug === 'guru') {
                return redirect('/guru/dashboard');
            } elseif ($user->role && $user->role->slug === 'siswa') {
                return redirect('/siswa/dashboard');
            } else {
                return response()->view('auth.unauthorized', [], 403);
            }
        }
        
        Log::warning('Emergency login failed for username: ' . $credentials['username']);
        
        return back()
            ->withInput($request->only('username'))
            ->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ]);
    }
}
