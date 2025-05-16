<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // If the user is logged in and trying to access a guest-only page (like login),
                // redirect them to the appropriate dashboard based on their role
                $user = Auth::guard($guard)->user();
                
                // Make sure the user has a role
                if (!$user->role) {
                    // No role assigned - log them out and send back to login
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('login')
                        ->withErrors(['username' => 'Account has no role assigned. Please contact administrator.']);
                }                  // If request is to the login page, redirect to their dashboard
                if ($request->routeIs('login') || $request->is('/') || $request->is('login')) {
                    // Use role slug directly to avoid method calls that might cause issues
                    $roleName = $user->role->slug ?? null;
                    
                    // Track redirects to prevent loops
                    $redirectCount = $request->session()->get('redirect_count', 0) + 1;
                    $request->session()->put('redirect_count', $redirectCount);
                    
                    // If we've redirected too many times, break the loop and show debug page
                    if ($redirectCount > 3) {
                        // Clear the counter and log the issue
                        $request->session()->forget('redirect_count');
                        \Log::error("Redirect loop detected for user {$user->id} with role {$roleName}");
                        
                        // Debug response to break the loop
                        return response()->view('admin.dashboard.debug', [
                            'user' => $user,
                            'debug_info' => [
                                'role' => $roleName,
                                'redirect_count' => $redirectCount,
                                'request_path' => $request->path()
                            ]
                        ]);
                    }
                    
                    switch ($roleName) {
                        case 'admin':
                            return redirect()->route('admin.dashboard');
                        case 'guru':
                            return redirect()->route('guru.dashboard');
                        case 'siswa':
                            return redirect()->route('siswa.dashboard');
                        default:
                            // Log this unexpected case
                            \Log::warning("User {$user->id} has unknown role: {$roleName}");
                            return redirect()->route('unauthorized');
                    }
                }
            }
        }

        return $next($request);
    }
}
