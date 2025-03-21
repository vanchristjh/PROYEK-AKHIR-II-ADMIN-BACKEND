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
                'name' => 'Dr. Slamet Widodo',
                'email' => 'slamet.widodo@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'phone_number' => '08123456789',
                'address' => 'Jl. Cendana No. 45, Jakarta',
                'birth_date' => '1980-03-15',
                'gender' => 'L',
                'nip' => '198003152010011001',
                'nuptk' => '1234567890123456',
                'subject' => 'Matematika',
                'position' => 'Guru Matematika',
                'join_date' => '2010-01-01',
                'education_level' => 'S3',
                'education_institution' => 'Universitas Indonesia',
            ],
            [
                'name' => 'Dra. Ratna Kusuma',
                'email' => 'ratna.kusuma@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'phone_number' => '08234567890',
                'address' => 'Jl. Melati No. 23, Jakarta',
                'birth_date' => '1985-06-20',
                'gender' => 'P',
                'nip' => '198506202011012002',
                'nuptk' => '2345678901234567',
                'subject' => 'Bahasa Indonesia',
                'position' => 'Guru Bahasa Indonesia',
                'join_date' => '2011-01-01',
                'education_level' => 'S2',
                'education_institution' => 'Universitas Pendidikan Indonesia',
            ],
            [
                'name' => 'Ir. Bambang Supriadi',
                'email' => 'bambang.supriadi@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'phone_number' => '08345678901',
                'address' => 'Jl. Anggrek No. 12, Jakarta',
                'birth_date' => '1975-12-10',
                'gender' => 'L',
                'nip' => '197512102009011003',
                'nuptk' => '3456789012345678',
                'subject' => 'Fisika',
                'position' => 'Kepala Laboratorium',
                'join_date' => '2009-01-01',
                'education_level' => 'S2',
                'education_institution' => 'Institut Teknologi Bandung',
            ],
            [
                'name' => 'Maya Indah, S.Pd.',
                'email' => 'maya.indah@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'phone_number' => '08456789012',
                'address' => 'Jl. Mawar No. 34, Jakarta',
                'birth_date' => '1988-09-25',
                'gender' => 'P',
                'nip' => '198809252012012004',
                'nuptk' => '4567890123456789',
                'subject' => 'Biologi',
                'position' => 'Guru Biologi',
                'join_date' => '2012-01-01',
                'education_level' => 'S1',
                'education_institution' => 'Universitas Negeri Jakarta',
            ],
            [
                'name' => 'Agus Hermawan, M.Pd.',
                'email' => 'agus.hermawan@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'phone_number' => '08567890123',
                'address' => 'Jl. Kenanga No. 56, Jakarta',
                'birth_date' => '1982-08-17',
                'gender' => 'L',
                'nip' => '198208172010011005',
                'nuptk' => '5678901234567890',
                'subject' => 'Sejarah',
                'position' => 'Wakil Kepala Sekolah',
                'join_date' => '2010-01-01',
                'education_level' => 'S2',
                'education_institution' => 'Universitas Negeri Yogyakarta',
            ],
        ];

        foreach ($teachers as $teacher) {
            User::create($teacher);
        }
    }
} 