<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
                'class' => 'X-A',
                'academic_year' => '2023/2024',
                'parent_name' => 'Ahmad Santoso',
                'parent_phone' => '08567891234',
            ],
            [
                'name' => 'Siti Nuraini',
                'email' => 'siti.nuraini@example.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone_number' => '08234567890',
                'address' => 'Jl. Pahlawan No. 45, Jakarta',
                'birth_date' => '2006-08-21',
                'gender' => 'P',
                'nis' => '1002',
                'nisn' => '9991002',
                'class' => 'X-A',
                'academic_year' => '2023/2024',
                'parent_name' => 'Hadi Nuraini',
                'parent_phone' => '08765432109',
            ],
            [
                'name' => 'Dimas Prakoso',
                'email' => 'dimas.prakoso@example.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone_number' => '08345678901',
                'address' => 'Jl. Kenanga No. 78, Jakarta',
                'birth_date' => '2006-11-03',
                'gender' => 'L',
                'nis' => '1003',
                'nisn' => '9991003',
                'class' => 'X-B',
                'academic_year' => '2023/2024',
                'parent_name' => 'Joko Prakoso',
                'parent_phone' => '08123987456',
            ],
            [
                'name' => 'Rina Anggraini',
                'email' => 'rina.anggraini@example.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone_number' => '08456789012',
                'address' => 'Jl. Anggrek No. 12, Jakarta',
                'birth_date' => '2006-02-18',
                'gender' => 'P',
                'nis' => '1004',
                'nisn' => '9991004',
                'class' => 'X-B',
                'academic_year' => '2023/2024',
                'parent_name' => 'Budi Anggraini',
                'parent_phone' => '08789456123',
            ],
            [
                'name' => 'Adi Nugroho',
                'email' => 'adi.nugroho@example.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone_number' => '08567890123',
                'address' => 'Jl. Dahlia No. 56, Jakarta',
                'birth_date' => '2005-07-30',
                'gender' => 'L',
                'nis' => '1005',
                'nisn' => '9991005',
                'class' => 'XI-A',
                'academic_year' => '2023/2024',
                'parent_name' => 'Hendra Nugroho',
                'parent_phone' => '08456123789',
            ],
        ];

        foreach ($students as $student) {
            User::create($student);
        }
    }
} 