<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AuthDebugController extends Controller
{
    public function showDebug(Request $request)
    {
        $userAuth = Auth::check();
        $userData = null;
        $routeInfo = [];
        
        // Get current user info
        if ($userAuth) {
            $user = Auth::user();
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'role_id' => $user->role_id,
                'role' => $user->role ? $user->role->name : 'No role',
                'role_slug' => $user->role ? $user->role->slug : 'No slug',
                'isAdmin' => $user->isAdmin(),
                'isGuru' => $user->isGuru(),
                'isStudent' => $user->isStudent(),
            ];
        }
        
        // Get cookie info
        $cookies = $request->cookies->all();
        $cookieInfo = [];
        foreach ($cookies as $name => $value) {
            $cookieInfo[] = [
                'name' => $name,
                'value' => strlen($value) > 20 ? substr($value, 0, 20) . '...' : $value,
            ];
        }
        
        // Get session info
        $sessionData = [
            'id' => session()->getId(),
            'has_cookie' => $request->hasCookie(config('session.cookie')),
            'cookie_name' => config('session.cookie'),
            'driver' => config('session.driver'),
        ];
        
        // Get route info
        $routeInfo = [
            'current_uri' => $request->getPathInfo(),
            'login_route' => route('login'),
            'admin_dashboard' => route('admin.dashboard'),
            'middleware' => $this->getMiddleware(),
        ];
        
        // Show view
        return view('auth.debug_tool', compact(
            'userAuth', 
            'userData', 
            'cookieInfo',
            'sessionData', 
            'routeInfo'
        ));
    }
    
    public function getMiddleware()
    {
        // If there's parent implementation, call it
        if (method_exists(parent::class, 'getMiddleware')) {
            return parent::getMiddleware();
        }
        
        // Otherwise return an empty array
        return [];
    }
}
