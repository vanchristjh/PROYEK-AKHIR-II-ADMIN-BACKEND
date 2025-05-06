<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Administrator has full access to all features',
            ],
            [
                'name' => 'Guru',
                'slug' => 'guru',
                'description' => 'Teachers can manage classes, grades, and students',
            ],
            [
                'name' => 'Siswa',
                'slug' => 'siswa',
                'description' => 'Students can view their grades, assignments, and schedules',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
