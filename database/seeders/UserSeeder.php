<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $guruRole = Role::where('slug', 'guru')->first();
        $siswaRole = Role::where('slug', 'siswa')->first();

        // Create admin user
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]
        );

        // Create guru (teacher) user
        User::firstOrCreate(
            ['username' => 'guru'],
            [
                'name' => 'Guru User',
                'email' => 'guru@example.com',
                'password' => Hash::make('password'),
                'role_id' => $guruRole->id,
            ]
        );

        // Create siswa (student) user
        User::firstOrCreate(
            ['username' => 'siswa'],
            [
                'name' => 'Siswa User',
                'email' => 'siswa@example.com',
                'password' => Hash::make('password'),
                'role_id' => $siswaRole->id,
            ]
        );
    }
}
