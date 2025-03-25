<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, we need to ensure we have at least one class
        $classId = null;
        
        // Check if class_rooms table exists
        if (ClassRoom::count() == 0) {
            $class = ClassRoom::create([
                'name' => 'X-A',
                'level' => 'X',
                'type' => 'IPA',
                'capacity' => 30,
                'room' => 'Ruang 101',
                'academic_year' => '2023/2024',
            ]);
            $classId = $class->id;
        } else {
            // Use an existing class
            $classId = ClassRoom::first()->id;
        }
        
        $students = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone_number' => '08123456789',
                'address' => 'Jl. Merdeka No. 123, Jakarta',
                'birth_date' => '2006-05-15',
                'gender' => 'L',
                'nis' => '1001',
                'nisn' => '9991001',
                'class_id' => $classId,
                'academic_year' => '2023/2024',
                'parent_name' => 'Slamet Santoso',
                'parent_phone' => '08765432109',
            ],
            [
                'name' => 'Siti Rahma',
                'email' => 'siti.rahma@example.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone_number' => '08234567890',
                'address' => 'Jl. Dahlia No. 45, Jakarta',
                'birth_date' => '2006-08-20',
                'gender' => 'P',
                'nis' => '1002',
                'nisn' => '9991002',
                'class_id' => $classId,
                'academic_year' => '2023/2024',
                'parent_name' => 'Ahmad Rahmat',
                'parent_phone' => '08654321098',
            ],
        ];
        
        foreach ($students as $student) {
            User::create($student);
        }
    }
}