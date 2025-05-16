<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class DebugController extends Controller
{
    public function checkAuth()
    {
        $routeMiddleware = Route::getMiddleware();
        $routeMiddlewareGroups = Route::getMiddlewareGroups();
        
        $html = '<h1>Auth Debug Info</h1>';
        
        // User info
        $html .= '<h2>User Info</h2>';
        if (Auth::check()) {
            $user = Auth::user();
            $html .= "<p>Authenticated as: {$user->name} (ID: {$user->id})</p>";
            $html .= "<p>Username: {$user->username}</p>";
            $html .= "<p>Role ID: {$user->role_id}</p>";
            
            $role = $user->role;
            if ($role) {
                $html .= "<p>Role: {$role->name}, Slug: {$role->slug}</p>";
            } else {
                $html .= "<p>No role found!</p>";
            }
        } else {
            $html .= "<p>Not authenticated!</p>";
        }
        
        // Sessions
        $html .= '<h2>Session Data</h2>';
        $html .= "<pre>" . print_r(session()->all(), true) . "</pre>";
        
        // Current route info
        $html .= '<h2>Current Route</h2>';
        $currentRoute = Route::current();
        if ($currentRoute) {
            $html .= "<p>Name: " . $currentRoute->getName() . "</p>";
            $html .= "<p>URI: " . $currentRoute->uri() . "</p>";
            $html .= "<p>Action: " . $currentRoute->getActionName() . "</p>";
            
            $html .= "<p>Middleware: " . implode(', ', $currentRoute->gatherMiddleware()) . "</p>";
        }
        
        // Link to guru assignments
        $html .= '<h2>Test Links</h2>';
        $html .= '<p><a href="' . route('guru.assignments.index') . '">Go to Guru Assignments</a></p>';
        
        return response($html);
    }
    
    public function tryGuruRoute()
    {
        return view('guru.assignments.index');
    }
}
