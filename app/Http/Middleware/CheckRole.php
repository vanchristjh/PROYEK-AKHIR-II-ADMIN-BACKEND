<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }
        
        // Permissive mode - allow access without role check
        return $next($request);
        
        /* Original role check removed
        $user = Auth::user();
        
        \Log::debug("CheckRole - User ID: {$user->id}, Roles required: " . implode(',', $roles));
        
        // Periksa jika user memiliki role
        if (!$user->role) {
            \Log::error("User {$user->id} doesn't have any role");
            return redirect()->route('login')->with('error', 'Akun Anda tidak memiliki peran yang valid. Hubungi administrator.');
        }
        
        \Log::debug("User role: {$user->role->slug}");
        
        foreach ($roles as $role) {
            if ($user->role->slug === strtolower($role)) {
                return $next($request);
            }
        }
        
        \Log::warning("Access denied for user {$user->id} with role {$user->role->slug}. Required roles: " . implode(',', $roles));
        return redirect()->route('unauthorized')->with('message', 'Anda tidak memiliki akses ke halaman ini.');
        */
    }
}
