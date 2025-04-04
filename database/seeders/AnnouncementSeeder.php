<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $announcements = [
            [
                'title' => 'Pengumuman Penting: Jadwal Ujian Semester',
                'content' => '<p>Diberitahukan kepada seluruh siswa bahwa jadwal ujian semester telah ditetapkan dan dapat dilihat pada papan pengumuman sekolah.</p><p>Persiapkan diri dengan baik dan jangan lupa membawa perlengkapan ujian yang diperlukan.</p>',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(2),
                'expired_at' => Carbon::now()->addDays(14),
                'target_audience' => 'students',
                'priority' => 'high',
                'created_by' => 1,
            ],
            [
                'title' => 'Rapat Guru dan Orang Tua',
                'content' => '<p>Diberitahukan kepada seluruh guru bahwa akan diadakan rapat dengan orang tua siswa pada:</p><ul><li>Hari/tanggal: Sabtu, ' . Carbon::now()->addDays(10)->format('d F Y') . '</li><li>Waktu: 09.00 WIB - selesai</li><li>Tempat: Aula Sekolah</li></ul><p>Mohon kehadiran Bapak/Ibu guru tepat waktu.</p>',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(1),
                'expired_at' => Carbon::now()->addDays(10),
                'target_audience' => 'teachers',
                'priority' => 'medium',
                'created_by' => 1,
            ],
            [
                'title' => 'Libur Hari Raya',
                'content' => '<p>Diberitahukan kepada seluruh civitas akademika SMA Negeri bahwa sekolah akan libur dalam rangka perayaan Hari Raya pada tanggal ' . Carbon::now()->addDays(20)->format('d F Y') . ' sampai dengan ' . Carbon::now()->addDays(25)->format('d F Y') . '.</p><p>Kegiatan belajar mengajar akan dimulai kembali pada tanggal ' . Carbon::now()->addDays(26)->format('d F Y') . '.</p>',
                'status' => 'published',
                'published_at' => Carbon::now(),
                'expired_at' => Carbon::now()->addDays(26),
                'target_audience' => 'all',
                'priority' => 'medium',
                'created_by' => 1,
            ],
            [
                'title' => 'Pengumpulan Tugas Akhir',
                'content' => '<p>Diberitahukan kepada seluruh siswa kelas XII bahwa batas waktu pengumpulan tugas akhir adalah ' . Carbon::now()->addDays(7)->format('d F Y') . '.</p><p>Mohon segera diselesaikan dan dikumpulkan kepada guru mata pelajaran masing-masing.</p>',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(3),
                'expired_at' => Carbon::now()->addDays(7),
                'target_audience' => 'students',
                'priority' => 'high',
                'created_by' => 1,
            ],
            [
                'title' => 'Kegiatan Ekstrakurikuler Semester Ini',
                'content' => '<p>Pendaftaran untuk kegiatan ekstrakurikuler semester ini telah dibuka. Siswa dapat mendaftar pada kegiatan berikut:</p><ul><li>Basket</li><li>Futsal</li><li>Pramuka</li><li>Paduan Suara</li><li>Robotik</li><li>Jurnalistik</li></ul><p>Pendaftaran dapat dilakukan melalui guru pembimbing masing-masing kegiatan.</p>',
                'status' => 'draft',
                'published_at' => null,
                'expired_at' => null,
                'target_audience' => 'students',
                'priority' => 'low',
                'created_by' => 1,
            ],
            [
                'title' => 'Pengumuman Hasil Ujian',
                'content' => '<p>Hasil ujian tengah semester telah diumumkan dan dapat dilihat pada akun siswa masing-masing.</p><p>Bagi yang belum mencapai KKM wajib mengikuti program remedial yang akan diadakan minggu depan.</p>',
                'status' => 'archived',
                'published_at' => Carbon::now()->subDays(30),
                'expired_at' => Carbon::now()->subDays(5),
                'target_audience' => 'students',
                'priority' => 'medium',
                'created_by' => 1,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }

        $this->command->info('Announcements seeded successfully!');
    }
}
