<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        // Debug logging to help troubleshoot
        Log::info('Login attempt for username: ' . $credentials['username']);
        
        // Track previous redirects to catch loops early
        $previousRedirects = $request->session()->get('_previous_redirects', []);
        if (count($previousRedirects) > 5) {
            Log::warning('Potential redirect loop detected in login process');
            $request->session()->forget('_previous_redirects');
        }
        
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            Log::info('Login successful for user: ' . Auth::user()->username);
            
            // Check if the role relationship exists
            $user = Auth::user();
            if (!$user->role) {
                Log::error('User has no role: ' . $user->id);
                Auth::logout();
                $request->session()->invalidate();
                return back()->withErrors(['username' => 'Account has no role assigned. Please contact administrator.']);
            }
            
            // Store intended URL to avoid redirect loops
            $request->session()->forget('url.intended');
            $request->session()->forget('_previous_redirects');
            
            // Use direct role checking for more reliable routing
            $roleName = $user->role->slug ?? 'unknown';
            $dashboardRoute = 'siswa.dashboard'; // Default
            
            // Clear any redirect loop tracking
            $request->session()->forget('redirect_count');
            
            switch($roleName) {
                case 'admin':
                    $dashboardRoute = 'admin.dashboard';
                    // Only try simple dashboard if we're having serious issues
                    if ($request->query('fallback') === 'true') {
                        Log::warning('Using fallback admin dashboard by request for user: ' . $user->id);
                        $dashboardRoute = 'admin.simple-dashboard';
                    }
                    break;
                    
                case 'guru':
                    $dashboardRoute = 'guru.dashboard';
                    break;
                    
                case 'siswa':
                    $dashboardRoute = 'siswa.dashboard';
                    break;
                    
                default:
                    Log::warning("Unknown role slug: {$roleName}");
            }
            
            // Track this redirect
            $previousRedirects[] = $dashboardRoute;
            $request->session()->put('_previous_redirects', array_slice($previousRedirects, -10));
            
            Log::info("Redirecting to {$dashboardRoute} for role {$roleName}");
            return redirect()->route($dashboardRoute);
        }

        Log::warning('Failed login attempt for username: ' . $credentials['username']);
        
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
