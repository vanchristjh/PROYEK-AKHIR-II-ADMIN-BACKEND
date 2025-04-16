<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = [
            [
                'name' => 'Dr. Ahmad Sulaiman, M.Pd.',
                'email' => 'ahmad.sulaiman@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'phone_number' => '08123456789',
                'address' => 'Jl. Pendidikan No. 45, Jakarta',
                'birth_date' => '1980-03-15',
                'gender' => 'L',
                'nip' => '198003152008011001',
                'nuptk' => '1234567890123456',
                'subject' => 'Matematika',
                'position' => 'Kepala Sekolah',
                'join_date' => '2010-01-01',
                'education_level' => 'S3',
                'education_institution' => 'Universitas Indonesia',
            ],
            [
                'name' => 'Dra. Siti Aminah',
                'email' => 'siti.aminah@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'phone_number' => '08234567890',
                'address' => 'Jl. Mawar No. 12, Jakarta',
                'birth_date' => '1985-05-20',
                'gender' => 'P',
                'nip' => '198505202009012002',
                'nuptk' => '2345678901234567',
                'subject' => 'Bahasa Indonesia',
                'position' => 'Wakil Kepala Sekolah',
                'join_date' => '2012-01-01',
                'education_level' => 'S2',
                'education_institution' => 'Universitas Negeri Jakarta',
            ],
        ];

        foreach ($teachers as $teacher) {
            // Check if teacher already exists before creating
            User::firstOrCreate(
                ['email' => $teacher['email']],
                $teacher
            );
        }
    }
}