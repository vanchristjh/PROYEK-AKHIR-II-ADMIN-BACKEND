<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, we need to ensure we have at least one class
        $classId = null;
        
        // Check if class_rooms table exists and has the necessary columns
        if (Schema::hasTable('class_rooms')) {
            $hasRequiredColumns = Schema::hasColumns('class_rooms', ['name', 'level', 'type', 'capacity', 'room', 'academic_year']);
            
            if ($hasRequiredColumns && ClassRoom::count() == 0) {
                $class = ClassRoom::create([
                    'name' => 'X-A',
                    'level' => 'X',
                    'type' => 'IPA',
                    'capacity' => 30,
                    'room' => 'Ruang 101',
                    'academic_year' => '2023/2024',
                ]);
                $classId = $class->id;
            } else if (ClassRoom::count() > 0) {
                // Use an existing class
                $classId = ClassRoom::first()->id;
            } else {
                // Create a class with only required fields
                try {
                    $class = new ClassRoom();
                    $class->name = 'X-A';
                    
                    // Add other fields only if they exist
                    if (Schema::hasColumn('class_rooms', 'level')) {
                        $class->level = 'X';
                    }
                    
                    if (Schema::hasColumn('class_rooms', 'type')) {
                        $class->type = 'IPA';
                    }
                    
                    $class->save();
                    $classId = $class->id;
                } catch (\Exception $e) {
                    $this->command->error("Error creating class: " . $e->getMessage());
                    // Create class using direct SQL to bypass model validation
                    $classId = DB::table('class_rooms')->insertGetId([
                        'name' => 'X-A',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } else {
            $this->command->error("class_rooms table doesn't exist! Creating students without class.");
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
                'academic_year' => '2023/2024',
                'parent_name' => 'Ahmad Rahmat',
                'parent_phone' => '08654321098',
            ],
        ];
        
        foreach ($students as $studentData) {
            // Add class_id to student data if class exists
            if (isset($classId)) {
                $studentData['class_id'] = $classId;
            }
            
            // Check if student already exists before creating
            User::firstOrCreate(
                ['email' => $studentData['email']],
                $studentData
            );
        }
    }
}