<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class RecreateClassesTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:recreate-classes-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recreate the classes table if it does not exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Schema::hasTable('classes')) {
            $this->info('Tabel classes tidak ditemukan. Membuat tabel...');
            
            try {
                // Jalankan migrasi khusus untuk membuat tabel classes
                Artisan::call('migrate', [
                    '--path' => 'database/migrations/2025_03_21_000001_create_classes_table_if_not_exists.php',
                    '--force' => true
                ]);
                
                $this->info('Tabel classes berhasil dibuat.');
                
                // Verifikasi apakah tabel berhasil dibuat
                if (Schema::hasTable('classes')) {
                    $this->info('Verifikasi: Tabel classes sudah ada di database.');
                } else {
                    $this->error('Verifikasi gagal: Tabel classes masih belum ada di database.');
                    return Command::FAILURE;
                }
                
                return Command::SUCCESS;
            } catch (\Exception $e) {
                $this->error('Gagal membuat tabel classes: ' . $e->getMessage());
                
                // Mencoba pendekatan alternatif dengan perintah SQL langsung
                if ($this->confirm('Coba buat tabel dengan SQL langsung?', true)) {
                    try {
                        DB::statement('
                            CREATE TABLE `classes` (
                                `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                                `name` varchar(255) NOT NULL,
                                `level` varchar(255) NOT NULL,
                                `type` varchar(255) NOT NULL,
                                `capacity` int(11) NOT NULL DEFAULT 30,
                                `room` varchar(255) DEFAULT NULL,
                                `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
                                `academic_year` varchar(255) DEFAULT NULL,
                                `description` text DEFAULT NULL,
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL,
                                PRIMARY KEY (`id`),
                                KEY `classes_teacher_id_foreign` (`teacher_id`),
                                CONSTRAINT `classes_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                        ');
                        
                        $this->info('Tabel classes berhasil dibuat dengan SQL langsung.');
                        return Command::SUCCESS;
                    } catch (\Exception $e2) {
                        $this->error('Gagal membuat tabel dengan SQL langsung: ' . $e2->getMessage());
                        return Command::FAILURE;
                    }
                }
                
                return Command::FAILURE;
            }
        } else {
            $this->info('Tabel classes sudah ada di database.');
            return Command::SUCCESS;
        }
    }
}
