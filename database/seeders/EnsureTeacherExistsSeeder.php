<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EnsureTeacherExistsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if teacher exists in the system
        $teacherExists = DB::table('users')
            ->where('role', 'teacher')
            ->exists();
            
        if (!$teacherExists) {
            // Create a sample teacher if none exists
            DB::table('users')->insert([
                'name' => 'Sample Teacher',
                'email' => 'teacher@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Sample teacher created to resolve foreign key constraints.');
        }
    }
}
