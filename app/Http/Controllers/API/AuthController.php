<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Login user and create token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
                'password' => 'required|string',
                'device_name' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->error('Validation error', 422, $validator->errors());
            }

            // Find user by username
            $user = User::where('username', $request->username)->first();

            // Check if user exists and password is correct
            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'username' => ['Username atau password salah.'],
                ]);
            }

            // Revoke old tokens for this device if exists
            if ($request->device_name) {
                $user->tokens()->where('name', $request->device_name)->delete();
            }

            // Create token
            $device = $request->device_name ?: 'Mobile App';
            $token = $user->createToken($device, [$user->role->slug])->plainTextToken;

            // Load relationships
            $user->load('role');

            return $this->success([
                'token' => $token,
                'user' => $user,
                'role' => $user->role->slug,
            ], 'Login berhasil');
        } catch (ValidationException $e) {
            return $this->error($e->getMessage(), 422, $e->errors());
        } catch (\Exception $e) {
            return $this->error('Login gagal: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get authenticated user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        try {
            $user = $request->user();
            $user->load(['role', 'classroom']);

            return $this->success($user, 'User data retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve user data: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Refresh token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'refresh_token' => 'required|string',
                'device_name' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->error('Validation error', 422, $validator->errors());
            }

            // Implement token refresh logic here
            // Note: Sanctum doesn't provide built-in refresh tokens
            // This is a placeholder for custom implementation

            return $this->error('Token refresh not implemented', 501);
        } catch (\Exception $e) {
            return $this->error('Token refresh failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Logout user (revoke token)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // Revoke current token
            $request->user()->currentAccessToken()->delete();

            return $this->success(null, 'Logout berhasil');
        } catch (\Exception $e) {
            return $this->error('Logout gagal: ' . $e->getMessage(), 500);
        }
    }
}