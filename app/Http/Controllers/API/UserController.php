<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Get the authenticated user profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        try {
            $user = Auth::user();
            $user->load(['role', 'classroom']);
            
            return $this->success($user, 'User profile retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve profile: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update the authenticated user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'sometimes|string|max:15',
                'address' => 'sometimes|string',
            ]);

            if ($validator->fails()) {
                return $this->error('Validation error', 422, $validator->errors());
            }
            
            $user->update($validator->validated());
            
            return $this->success($user, 'Profile updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update profile: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Change the authenticated user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return $this->error('Validation error', 422, $validator->errors());
            }
            
            $user = Auth::user();
            
            // Check current password
            if (!Hash::check($request->current_password, $user->password)) {
                return $this->error('Current password is incorrect', 422);
            }
            
            $user->password = Hash::make($request->password);
            $user->save();
            
            return $this->success(null, 'Password changed successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to change password: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Upload user avatar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAvatar(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'avatar' => 'required|image|max:2048', // 2MB max
            ]);

            if ($validator->fails()) {
                return $this->error('Validation error', 422, $validator->errors());
            }
            
            $user = Auth::user();
            
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            $user->save();
            
            return $this->success([
                'avatar_url' => asset('storage/' . $path)
            ], 'Avatar uploaded successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to upload avatar: ' . $e->getMessage(), 500);
        }
    }

    /**
     * List all users (for admin).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $roleFilter = $request->input('role');
            
            $query = User::with('role');
            
            // Apply search
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('username', 'like', "%$search%");
                });
            }
            
            // Apply role filter
            if ($roleFilter) {
                $query->whereHas('role', function($q) use ($roleFilter) {
                    $q->where('slug', $roleFilter);
                });
            }
            
            $users = $query->orderBy('name')->paginate($perPage);
            
            return $this->success($users, 'Users retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve users: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Show user details (for admin).
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        try {
            $user->load(['role', 'classroom']);
            
            return $this->success($user, 'User details retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve user details: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store a new user (for admin).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role_id' => 'required|exists:roles,id',
                'classroom_id' => 'nullable|exists:classrooms,id',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->error('Validation error', 422, $validator->errors());
            }
            
            $data = $validator->validated();
            $data['password'] = Hash::make($data['password']);
            
            $user = User::create($data);
            $user->load(['role', 'classroom']);
            
            return $this->success($user, 'User created successfully', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to create user: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update user details (for admin).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $user->id,
                'username' => 'sometimes|string|max:255|unique:users,username,' . $user->id,
                'password' => 'sometimes|string|min:8',
                'role_id' => 'sometimes|exists:roles,id',
                'classroom_id' => 'nullable|exists:classrooms,id',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->error('Validation error', 422, $validator->errors());
            }
            
            $data = $validator->validated();
            
            // Only hash password if it's being updated
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            
            $user->update($data);
            $user->load(['role', 'classroom']);
            
            return $this->success($user, 'User updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update user: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Delete a user (for admin).
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        try {
            // Prevent deleting yourself
            if (Auth::id() === $user->id) {
                return $this->error('You cannot delete your own account', 403);
            }
            
            // Delete avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $user->delete();
            
            return $this->success(null, 'User deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete user: ' . $e->getMessage(), 500);
        }
    }
}