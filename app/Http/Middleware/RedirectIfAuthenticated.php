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
                }
                
                // If request is to the login page, redirect to their dashboard
                if ($request->routeIs('login') || $request->is('/') || $request->is('login')) {
                    if ($user->isAdmin()) {
                        return redirect()->route('admin.dashboard');
                    } elseif ($user->isGuru()) {
                        return redirect()->route('guru.dashboard');
                    } elseif ($user->isStudent()) {
                        return redirect()->route('siswa.dashboard');
                    } else {
                        // Fallback for unknown role
                        return redirect()->route('unauthorized');
                    }
                }
            }
        }

        return $next($request);
    }
}
