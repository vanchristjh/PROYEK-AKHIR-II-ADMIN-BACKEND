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
        
        // Add debug logging
        \Log::debug("RoleMiddleware check - User ID: {$user->id}, Required role: {$role}");
        
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
        
        // Extra debugging
        \Log::debug("User role from database: {$userRole}, Required role: {$role}");
        
        // If path contains admin/dashboard and we're having redirect issues, let it through
        $isAdminDashboardPath = strpos($request->path(), 'admin/dashboard') !== false;
        $redirectCount = $request->session()->get('redirect_count', 0);
        
        if ($redirectCount > 2 && $isAdminDashboardPath && $userRole === 'admin') {
            \Log::warning("Bypassing strict role check due to potential redirect loop for admin: {$user->id}");
            $request->session()->forget('redirect_count');
            return $next($request);
        }
        
        // Normal role validation
        if ($userRole !== $role) {
            // Add more detailed logging for debugging
            \Log::warning("Role mismatch for user: " . $user->id . ", User role: " . $userRole . ", Required role: " . $role);
            return redirect()->route('unauthorized');
        }
        
        // Role check passed
        \Log::debug("Role check passed for user {$user->id}");
        $request->session()->forget('redirect_count'); // Clear any redirect tracking if successful
        return $next($request);
    }
}
