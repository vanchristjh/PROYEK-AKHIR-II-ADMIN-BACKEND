<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get admin user
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $this->command->info('Admin user not found. Skipping notification seeding.');
            return;
        }
        
        $notifications = [
            [
                'user_id' => $admin->id,
                'title' => 'Pendaftaran Siswa',
                'message' => '5 siswa baru telah terdaftar',
                'icon' => 'bx-user-plus',
                'icon_background' => 'bg-primary-light text-primary',
                'created_at' => Carbon::now()->subMinutes(30),
                'is_important' => true
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Jadwal Baru',
                'message' => 'Jadwal semester ganjil telah diterbitkan',
                'icon' => 'bx-calendar-check',
                'icon_background' => 'bg-success-light text-success',
                'created_at' => Carbon::now()->subHours(2),
                'is_important' => false
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Pengumpulan Nilai',
                'message' => 'Pengumpulan nilai semester terakhir besok',
                'icon' => 'bx-error-circle',
                'icon_background' => 'bg-warning-light text-warning',
                'created_at' => Carbon::now()->subHours(12),
                'is_important' => true
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Rapat Guru',
                'message' => 'Rapat guru akan diadakan hari Jumat',
                'icon' => 'bx-bell',
                'icon_background' => 'bg-info-light text-info',
                'created_at' => Carbon::now()->subDays(1),
                'link' => '/academic-calendar'
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Ujian Semester',
                'message' => 'Ujian semester dimulai dalam 2 minggu',
                'icon' => 'bx-calendar-exclamation',
                'icon_background' => 'bg-danger-light text-danger',
                'created_at' => Carbon::now()->subDays(2),
                'is_important' => true,
                'link' => '/academic-calendar'
            ]
        ];
        
        foreach ($notifications as $notification) {
            Notification::create($notification);
        }
        
        $this->command->info('Notification seeding completed.');
    }
}
