<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AcademicCalendar;
use Carbon\Carbon;

class AcademicCalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'Ujian Akhir Semester Genap',
                'description' => 'Ujian akan dilaksanakan untuk semua mata pelajaran. Siswa diharapkan mempersiapkan diri dengan baik.',
                'start_date' => Carbon::now()->addDays(20)->setTime(8, 0),
                'end_date' => Carbon::now()->addDays(25)->setTime(15, 0),
                'location' => 'Ruang Kelas',
                'event_type' => 'exam',
                'is_important' => true,
                'created_by' => 1,
                'academic_year' => '2023/2024',
                'semester' => '2',
                'target_audience' => 'students',
            ],
            [
                'title' => 'Rapat Guru dan Staf',
                'description' => 'Pembahasan kurikulum dan evaluasi pembelajaran semester berjalan',
                'start_date' => Carbon::now()->addDays(5)->setTime(13, 0),
                'end_date' => Carbon::now()->addDays(5)->setTime(15, 0),
                'location' => 'Ruang Rapat Utama',
                'event_type' => 'meeting',
                'is_important' => false,
                'created_by' => 1,
                'academic_year' => '2023/2024',
                'semester' => '2',
                'target_audience' => 'teachers',
            ],
            [
                'title' => 'Libur Hari Raya',
                'description' => 'Sekolah akan libur selama hari raya. Kegiatan belajar mengajar akan ditiadakan.',
                'start_date' => Carbon::now()->addDays(30)->setTime(0, 0),
                'end_date' => Carbon::now()->addDays(35)->setTime(23, 59),
                'location' => '',
                'event_type' => 'holiday',
                'is_important' => true,
                'created_by' => 1,
                'academic_year' => '2023/2024',
                'semester' => '2',
                'target_audience' => 'all',
            ],
            [
                'title' => 'Kegiatan Ekstrakurikuler: Kompetisi Robotik',
                'description' => 'Kompetisi robotik antar kelas akan diselenggarakan. Siswa dapat mendaftar melalui wali kelas masing-masing.',
                'start_date' => Carbon::now()->addDays(15)->setTime(14, 0),
                'end_date' => Carbon::now()->addDays(15)->setTime(17, 0),
                'location' => 'Aula Sekolah',
                'event_type' => 'extracurricular',
                'is_important' => false,
                'created_by' => 1,
                'academic_year' => '2023/2024',
                'semester' => '2',
                'target_audience' => 'students',
            ],
            [
                'title' => 'Pendaftaran Siswa Baru',
                'description' => 'Pendaftaran siswa baru tahun ajaran 2024/2025 akan dibuka. Formulir pendaftaran dapat diambil di ruang administrasi.',
                'start_date' => Carbon::now()->addDays(60)->setTime(8, 0),
                'end_date' => Carbon::now()->addDays(90)->setTime(15, 0),
                'location' => 'Ruang Administrasi',
                'event_type' => 'academic',
                'is_important' => true,
                'created_by' => 1,
                'academic_year' => '2024/2025',
                'semester' => '1',
                'target_audience' => 'all',
            ],
            [
                'title' => 'Persiapan Ujian Nasional',
                'description' => 'Bimbingan intensif persiapan ujian nasional untuk siswa kelas XII',
                'start_date' => Carbon::now()->addDays(45)->setTime(14, 0),
                'end_date' => Carbon::now()->addDays(55)->setTime(17, 0),
                'location' => 'Ruang Kelas XII',
                'event_type' => 'academic',
                'is_important' => true,
                'created_by' => 1,
                'academic_year' => '2023/2024',
                'semester' => '2',
                'target_audience' => 'students',
            ],
            [
                'title' => 'Kegiatan Sedang Berlangsung',
                'description' => 'Ini adalah kegiatan yang sedang berlangsung saat ini',
                'start_date' => Carbon::now()->subHours(2),
                'end_date' => Carbon::now()->addHours(3),
                'location' => 'Aula Utama',
                'event_type' => 'academic',
                'is_important' => false,
                'created_by' => 1,
                'academic_year' => '2023/2024',
                'semester' => '2',
                'target_audience' => 'all',
            ],
        ];

        foreach ($events as $event) {
            AcademicCalendar::create($event);
        }
    }
}
