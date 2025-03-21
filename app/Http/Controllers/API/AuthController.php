<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Login user and create token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string',
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password yang anda masukkan salah.'],
            ]);
        }
        
        $deviceName = $request->device_name ?? $request->userAgent() ?? 'Unknown Device';
        $token = $user->createToken($deviceName)->plainTextToken;
        
        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Login berhasil'
        ], 200);
    }
    
    /**
     * Register a new user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // Note: This endpoint is disabled by default as registration 
        // should be handled through the web interface for security
        return response()->json([
            'message' => 'Registration is only available through the admin dashboard'
        ], 403);
    }
    
    /**
     * Logout user (Revoke the token)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
} 