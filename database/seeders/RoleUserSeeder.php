<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Role;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();
        
        foreach ($users as $user) {
            // Check if the user has a default role based on your application logic
            // For example, based on email domain, user type, etc.
            $roleId = $this->determineUserRole($user);
              // Insert into role_user table
            if ($roleId) {
                // Check if relationship already exists
                $exists = DB::table('role_user')
                    ->where('role_id', $roleId)
                    ->where('user_id', $user->id)
                    ->exists();
                
                if (!$exists) {
                    DB::table('role_user')->insert([
                        'role_id' => $roleId,
                        'user_id' => $user->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
    
    /**
     * Determine the role ID for a user based on application logic
     * 
     * @param User $user
     * @return int|null
     */
    private function determineUserRole(User $user): ?int
    {
        // This is just a placeholder - replace with your actual logic
        // For example, if you already have a role relation on your User model:
        if (Schema::hasColumn('users', 'role_id') && $user->role_id) {
            return $user->role_id;
        }
        
        // Or determine role based on email domain
        if (str_ends_with($user->email, 'admin.com')) {
            return Role::where('name', 'admin')->first()?->id;
        } elseif (str_ends_with($user->email, 'guru.com')) {
            return Role::where('name', 'guru')->first()?->id;
        } elseif (str_ends_with($user->email, 'siswa.com')) {
            return Role::where('name', 'siswa')->first()?->id;
        }
        
        // Default role
        return Role::where('name', 'siswa')->first()?->id;
    }
}
