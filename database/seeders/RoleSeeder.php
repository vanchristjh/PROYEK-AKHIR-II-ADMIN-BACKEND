<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */    public function run()
    {
        $roles = [
            ['name' => 'admin', 'slug' => 'admin', 'description' => 'Administrator'],
            ['name' => 'teacher', 'slug' => 'guru', 'description' => 'Teacher'],
            ['name' => 'student', 'slug' => 'siswa', 'description' => 'Student'],
            ['name' => 'parent', 'slug' => 'parent', 'description' => 'Parent'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']],
                [
                    'slug' => $role['slug'],
                    'description' => $role['description'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
