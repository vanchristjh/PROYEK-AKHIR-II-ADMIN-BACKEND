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
                'name' => 'Matematika',
                'code' => 'MTK',
                'description' => 'Mata pelajaran tentang konsep dan operasi matematika',
                'class_level' => 'X',
                'semester' => '1',
                'curriculum' => 'Kurikulum Merdeka',
                'is_active' => true,
                'credits' => 4,
                'subject_type' => 'Wajib',
            ],
            [
                'name' => 'Bahasa Indonesia',
                'code' => 'BIN',
                'description' => 'Mata pelajaran tentang bahasa dan sastra Indonesia',
                'class_level' => 'X',
                'semester' => '1',
                'curriculum' => 'Kurikulum Merdeka',
                'is_active' => true,
                'credits' => 3,
                'subject_type' => 'Wajib',
            ],
            [
                'name' => 'Bahasa Inggris',
                'code' => 'BIG',
                'description' => 'Mata pelajaran tentang bahasa Inggris',
                'class_level' => 'X',
                'semester' => '1',
                'curriculum' => 'Kurikulum Merdeka',
                'is_active' => true,
                'credits' => 3,
                'subject_type' => 'Wajib',
            ],
            [
                'name' => 'Fisika',
                'code' => 'FIS',
                'description' => 'Mata pelajaran tentang konsep-konsep fisika',
                'class_level' => 'X',
                'semester' => '1',
                'curriculum' => 'Kurikulum Merdeka',
                'is_active' => true,
                'credits' => 4,
                'subject_type' => 'Peminatan',
            ],
            [
                'name' => 'Kimia',
                'code' => 'KIM',
                'description' => 'Mata pelajaran tentang konsep-konsep kimia',
                'class_level' => 'X',
                'semester' => '1',
                'curriculum' => 'Kurikulum Merdeka',
                'is_active' => true,
                'credits' => 3,
                'subject_type' => 'Peminatan',
            ],
            [
                'name' => 'Biologi',
                'code' => 'BIO',
                'description' => 'Mata pelajaran tentang makhluk hidup dan lingkungannya',
                'class_level' => 'X',
                'semester' => '1',
                'curriculum' => 'Kurikulum Merdeka',
                'is_active' => true,
                'credits' => 3,
                'subject_type' => 'Peminatan',
            ],
            [
                'name' => 'Sejarah',
                'code' => 'SEJ',
                'description' => 'Mata pelajaran tentang sejarah Indonesia dan dunia',
                'class_level' => 'X',
                'semester' => '1',
                'curriculum' => 'Kurikulum Merdeka',
                'is_active' => true,
                'credits' => 2,
                'subject_type' => 'Wajib',
            ],
            [
                'name' => 'Ekonomi',
                'code' => 'EKO',
                'description' => 'Mata pelajaran tentang konsep-konsep ekonomi',
                'class_level' => 'X',
                'semester' => '1',
                'curriculum' => 'Kurikulum Merdeka',
                'is_active' => true,
                'credits' => 3,
                'subject_type' => 'Peminatan',
            ],
            [
                'name' => 'Pendidikan Agama',
                'code' => 'PAI',
                'description' => 'Mata pelajaran tentang pendidikan agama',
                'class_level' => null,
                'semester' => null,
                'curriculum' => 'Kurikulum Merdeka',
                'is_active' => true,
                'credits' => 2,
                'subject_type' => 'Wajib',
            ],
            [
                'name' => 'Pendidikan Kewarganegaraan',
                'code' => 'PKN',
                'description' => 'Mata pelajaran tentang pendidikan kewarganegaraan',
                'class_level' => null,
                'semester' => null,
                'curriculum' => 'Kurikulum Merdeka',
                'is_active' => true,
                'credits' => 2,
                'subject_type' => 'Wajib',
            ],
        ];

        foreach ($subjects as $subjectData) {
            $subject = Subject::create($subjectData);

            // Assign teachers with matching subjects to their respective subjects
            $matchingTeachers = User::where('role', 'teacher')
                ->where(function ($query) use ($subjectData) {
                    $query->where('subject', 'like', '%' . $subjectData['name'] . '%')
                        ->orWhere('subject', 'like', '%' . $subjectData['code'] . '%');
                })
                ->get();

            if ($matchingTeachers->count() > 0) {
                $subject->teachers()->attach($matchingTeachers->pluck('id')->toArray());
            }
        }
    }
}
