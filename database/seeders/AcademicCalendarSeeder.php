<?php

namespace Database\Seeders;

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
                'title' => 'Tahun Ajaran Baru',
                'description' => 'Dimulainya tahun ajaran baru 2023/2024',
                'start_date' => Carbon::create(2023, 7, 17),
                'end_date' => Carbon::create(2023, 7, 17),
                'event_type' => 'academic',
                'is_important' => true,
                'color' => '#0d6efd', // blue
                'created_by' => 1,
                'target_audience' => 'all',
                'academic_year' => '2023/2024'
            ],
            [
                'title' => 'Ujian Tengah Semester',
                'description' => 'Ujian Tengah Semester Ganjil 2023/2024',
                'start_date' => Carbon::create(2023, 10, 9),
                'end_date' => Carbon::create(2023, 10, 14),
                'event_type' => 'exam',
                'is_important' => true,
                'color' => '#fd7e14', // orange
                'created_by' => 1,
                'target_audience' => 'all',
                'academic_year' => '2023/2024',
                'semester' => '1'
            ],
            [
                'title' => 'Libur Hari Kemerdekaan',
                'description' => 'Libur memperingati Hari Kemerdekaan RI',
                'start_date' => Carbon::create(2023, 8, 17),
                'end_date' => Carbon::create(2023, 8, 17),
                'event_type' => 'holiday',
                'is_important' => true,
                'color' => '#dc3545', // red
                'created_by' => 1,
                'target_audience' => 'all'
            ],
        ];

        foreach ($events as $event) {
            AcademicCalendar::create($event);
        }
    }
}
