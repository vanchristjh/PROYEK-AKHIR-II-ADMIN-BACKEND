<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugAuthFlow
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if we're in debug mode (only log when APP_DEBUG is true)
        if (!config('app.debug')) {
            return $next($request);
        }

        // Get the current route and user
        $routeName = $request->route() ? $request->route()->getName() : 'unknown';
        $routePath = $request->path();
        $isAuth = Auth::check();
        $userId = Auth::id();
        $userRole = null;
        
        if ($isAuth) {
            $user = Auth::user();
            $userRole = $user->role ? $user->role->slug : 'no-role';
            
            // Check for repeated redirects (potential loop)
            $lastRedirects = session('_debug_last_redirects', []);
            
            // Store current route in session for redirect tracking
            if ($request->method() === 'GET') {
                $lastRedirects[] = $routePath;
                // Keep only last 5 redirects
                $lastRedirects = array_slice($lastRedirects, -5);
                session(['_debug_last_redirects' => $lastRedirects]);
                
                // Check for potential loops (same URL 3+ times)
                $redirectCounts = array_count_values($lastRedirects);
                foreach ($redirectCounts as $path => $count) {
                    if ($count >= 3) {
                        Log::warning("⚠️ REDIRECT LOOP DETECTED: Path '{$path}' was visited {$count} times in succession");
                        
                        // If we're in a loop and trying to access admin pages, let's try to break the loop
                        if (strpos($path, 'admin') !== false) {
                            Auth::logout();
                            $request->session()->invalidate();
                            $request->session()->regenerateToken();
                            
                            Log::info("🔄 Breaking redirect loop: User logged out");
                            
                            // Redirect to login with warning
                            return redirect()->route('login')
                                ->with('warning', 'Redirect loop detected. You have been logged out to prevent browser errors.');
                        }
                    }
                }
            }
        }

        // Log request info
        Log::debug("🔍 AUTH FLOW: " . ($isAuth ? "Authenticated" : "Guest") . 
                  " | Route: {$routeName} ({$routePath}) " . 
                  ($isAuth ? "| User: {$userId} | Role: {$userRole}" : ""));

        // Proceed with request
        $response = $next($request);

        // If it's a redirect, log that too
        if ($response->isRedirect()) {
            $redirectTo = $response->getTargetUrl();
            Log::debug("➡️ REDIRECT: From {$routePath} to {$redirectTo}");
        }

        return $response;
    }
}
