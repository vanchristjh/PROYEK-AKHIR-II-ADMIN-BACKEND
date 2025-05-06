<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class FixUserAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:fix-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix or recreate default user accounts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking and fixing user accounts...');

        $adminRole = Role::where('name', 'admin')->first();
        $guruRole = Role::where('name', 'guru')->first();
        $siswaRole = Role::where('name', 'siswa')->first();

        if (!$adminRole || !$guruRole || !$siswaRole) {
            $this->error('Required roles not found. Please run php artisan db:seed --class=RoleSeeder first.');
            return 1;
        }

        // Fix admin user
        $this->fixUser('admin', 'admin@example.com', 'Admin User', 'password', $adminRole->id);
        
        // Fix guru user
        $this->fixUser('guru', 'guru@example.com', 'Guru User', 'password', $guruRole->id);
        
        // Fix siswa user
        $this->fixUser('siswa', 'siswa@example.com', 'Siswa User', 'password', $siswaRole->id);

        $this->info('User accounts have been checked and fixed successfully!');
        $this->info('You can now log in with the following credentials:');
        $this->info('Admin: username=admin, password=password');
        $this->info('Guru: username=guru, password=password');
        $this->info('Siswa: username=siswa, password=password');

        return 0;
    }

    /**
     * Fix or create a user account
     */
    private function fixUser($username, $email, $name, $password, $roleId)
    {
        $user = User::where('username', $username)->first();
        
        if ($user) {
            $this->info("Updating existing user: {$username}");
            $user->update([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role_id' => $roleId,
            ]);
        } else {
            $this->info("Creating new user: {$username}");
            User::create([
                'username' => $username,
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role_id' => $roleId,
            ]);
        }
    }
}
