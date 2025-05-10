<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            // Not logged in, redirect to login
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Check if user has a role
        if (!$user->role) {
            // Log the issue
            \Log::error('User has no role defined: ' . $user->id);
            
            // Logout the user and invalidate session
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->withErrors(['username' => 'Your account has no role assigned. Please contact administrator.']);
        }
        
        $userRole = $user->role->slug;
        
        if ($userRole !== $role) {
            return redirect()->route('unauthorized');
        }
        
        return $next($request);
    }
}
