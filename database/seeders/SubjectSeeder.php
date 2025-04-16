<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\User;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'Bahasa Indonesia',
                'code' => 'BIN',
                'description' => 'Mata pelajaran tentang bahasa dan sastra Indonesia',
                'class_level' => 'X',
                'semester' => '1',
                'curriculum' => 'Kurikulum Merdeka',
                'is_active' => true,
                'credits' => 3,
                'subject_type' => 'Wajib'
            ],
            [
                'name' => 'Matematika',
                'code' => 'MTK',
                'description' => 'Mata pelajaran tentang konsep dan operasi matematika',
                'class_level' => 'X',
                'semester' => '1',
                'curriculum' => 'Kurikulum Merdeka',
                'is_active' => true,
                'credits' => 4,
                'subject_type' => 'Wajib'
            ],
            [
                'name' => 'Bahasa Inggris',
                'code' => 'ENG',
                'description' => 'Mata pelajaran tentang bahasa Inggris dan komunikasi global',
                'class_level' => 'X',
                'semester' => '1',
                'curriculum' => 'Kurikulum Merdeka',
                'is_active' => true,
                'credits' => 3,
                'subject_type' => 'Wajib'
            ],
        ];
        
        foreach ($subjects as $subject) {
            // Use firstOrCreate to prevent duplicate entries
            Subject::firstOrCreate(
                ['code' => $subject['code']], // Check if subject with this code already exists
                $subject // Data to create if not found
            );
        }
    }
}
