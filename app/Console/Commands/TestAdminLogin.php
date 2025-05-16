<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TestAdminLogin extends Command
{
    protected $signature = 'test:admin-login {username?} {password?}';
    protected $description = 'Test admin login functionality without browser redirect issues';

    public function handle()
    {
        $username = $this->argument('username') ?? $this->ask('Enter admin username');
        $password = $this->argument('password') ?? $this->secret('Enter admin password');
        
        $this->info('Testing login for ' . $username);
        
        // Attempt login
        $user = User::where('username', $username)->first();
        
        if (!$user) {
            $this->error('User not found');
            return 1;
        }
        
        $this->info('User found: ID=' . $user->id);
        
        if ($user->role) {
            $this->info('Role: ' . $user->role->name . ' (' . $user->role->slug . ')');
        } else {
            $this->error('User has no role assigned');
            return 1;
        }
        
        // Test password
        if (Hash::check($password, $user->password)) {
            $this->info('Password is correct');
            
            // Test auth logic
            Auth::login($user);
            
            if (Auth::check()) {
                $this->info('Authentication successful');
                
                // Test redirect logic
                if ($user->role->slug === 'admin') {
                    $this->info('User would be redirected to admin.dashboard');
                } elseif ($user->role->slug === 'guru') {
                    $this->info('User would be redirected to guru.dashboard');
                } else {
                    $this->info('User would be redirected to siswa.dashboard');
                }
                
                // Test logout
                Auth::logout();
                $this->info('Logout successful');
                
                return 0;
            } else {
                $this->error('Authentication failed despite correct password');
                return 1;
            }
        } else {
            $this->error('Password is incorrect');
            return 1;
        }
    }
}
