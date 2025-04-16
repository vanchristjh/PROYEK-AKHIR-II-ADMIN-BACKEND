<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str; // Added import for Str class

class FixMySQLDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:fix-mysql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix MySQL database issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Pastikan menggunakan MySQL
        if (config('database.default') !== 'mysql') {
            $this->error('Command ini hanya untuk database MySQL.');
            return Command::FAILURE;
        }

        $this->info('Memeriksa dan memperbaiki masalah database MySQL...');

        // Cek koneksi database
        try {
            DB::connection()->getPdo();
            $this->info('Koneksi database berhasil!');
        } catch (\Exception $e) {
            $this->error('Koneksi database gagal: ' . $e->getMessage());
            
            if ($this->confirm('Apakah Anda ingin memeriksa konfigurasi .env?', true)) {
                $this->checkEnvConfig();
            }
            
            return Command::FAILURE;
        }

        // Cek struktur tabel dan masalah foreign key
        $this->fixClassTables();
        
        // Cek masalah pada foreign key teacher_id dan class_id
        $this->checkForeignKeyConstraints();

        $this->info('Proses perbaikan database selesai!');
        return Command::SUCCESS;
    }

    private function checkEnvConfig()
    {
        $this->info('Periksa konfigurasi database di file .env Anda:');
        $this->line('DB_CONNECTION=mysql');
        $this->line('DB_HOST=127.0.0.1');
        $this->line('DB_PORT=3306');
        $this->line('DB_DATABASE=nama_database_anda');
        $this->line('DB_USERNAME=username_database');
        $this->line('DB_PASSWORD=password_database');
        
        $this->info('Pastikan database telah dibuat dan user memiliki akses yang cukup.');
    }

    private function fixClassTables()
    {
        // Cek apakah kedua tabel classes dan class_rooms ada
        $hasClasses = Schema::hasTable('classes');
        $hasClassRooms = Schema::hasTable('class_rooms');
        
        if ($hasClasses && $hasClassRooms) {
            $this->warn('Tabel classes dan class_rooms keduanya ada. Ini bisa menyebabkan kebingungan.');
            
            if ($this->confirm('Apakah Anda ingin memigrasikan data dari classes ke class_rooms dan menghapus tabel classes?', true)) {
                // Migrasikan data dari classes ke class_rooms
                $classes = DB::table('classes')->get();
                
                foreach ($classes as $class) {
                    // Periksa apakah teacher_id ada di tabel users
                    if ($class->teacher_id) {
                        $teacherExists = DB::table('users')
                            ->where('id', $class->teacher_id)
                            ->where('role', 'teacher')
                            ->exists();
                            
                        if (!$teacherExists) {
                            $this->warn("Guru dengan ID {$class->teacher_id} tidak ada. Menetapkan ke null.");
                            $class->teacher_id = null;
                        }
                    }
                    
                    // Masukkan ke class_rooms
                    DB::table('class_rooms')->insertOrIgnore([
                        'name' => $class->name,
                        'level' => $class->level,
                        'type' => $class->type,
                        'capacity' => $class->capacity,
                        'teacher_id' => $class->teacher_id,
                        'room' => $class->room,
                        'academic_year' => $class->academic_year,
                        'description' => $class->description,
                        'created_at' => $class->created_at,
                        'updated_at' => $class->updated_at,
                    ]);
                }
                
                // Update tabel users untuk menggunakan class_rooms daripada classes
                if (Schema::hasColumn('users', 'class_id')) {
                    $this->info('Memperbarui referensi kelas siswa...');
                    
                    $students = DB::table('users')
                        ->whereNotNull('class_id')
                        ->get();
                        
                    foreach ($students as $student) {
                        // Temukan kelas yang cocok di class_rooms
                        $originalClass = DB::table('classes')
                            ->where('id', $student->class_id)
                            ->first();
                            
                        if ($originalClass) {
                            // Temukan kelas yang cocok di class_rooms
                            $newClass = DB::table('class_rooms')
                                ->where('name', $originalClass->name)
                                ->where('level', $originalClass->level)
                                ->where('type', $originalClass->type)
                                ->first();
                                
                            if ($newClass) {
                                DB::table('users')
                                    ->where('id', $student->id)
                                    ->update(['class_id' => $newClass->id]);
                            }
                        }
                    }
                }
                
                // Hapus constraint foreign key jika ada
                if (Schema::hasTable('users') && Schema::hasColumn('users', 'class_id')) {
                    try {
                        // Dapatkan indeks foreign key
                        $foreignKeys = $this->getForeignKeys('users');
                        foreach ($foreignKeys as $foreignKey) {
                            if (strpos($foreignKey, 'class_id') !== false) {
                                DB::statement("ALTER TABLE users DROP FOREIGN KEY $foreignKey");
                                $this->info("Menghapus foreign key constraint: $foreignKey");
                            }
                        }
                    } catch (\Exception $e) {
                        $this->warn("Gagal menghapus foreign key: " . $e->getMessage());
                    }
                }
                
                // Hapus tabel classes
                try {
                    Schema::dropIfExists('classes');
                    $this->info('Tabel classes berhasil dihapus setelah migrasi data.');
                } catch (\Exception $e) {
                    $this->error('Gagal menghapus tabel classes: ' . $e->getMessage());
                }
                
                // Tambahkan constraint foreign key yang benar ke class_rooms
                if (Schema::hasTable('users') && Schema::hasColumn('users', 'class_id')) {
                    try {
                        DB::statement("ALTER TABLE users ADD CONSTRAINT users_class_id_foreign FOREIGN KEY (class_id) REFERENCES class_rooms(id) ON DELETE SET NULL");
                        $this->info('Menambahkan foreign key constraint ke class_rooms');
                    } catch (\Exception $e) {
                        $this->warn("Gagal menambahkan foreign key constraint: " . $e->getMessage());
                    }
                }
            }
        } else if (!$hasClasses && !$hasClassRooms) {
            $this->warn('Tabel classes dan class_rooms keduanya tidak ada.');
            
            if ($this->confirm('Apakah Anda ingin menjalankan migrasi untuk membuat tabel?', true)) {
                try {
                    Artisan::call('migrate', ['--force' => true]);
                    $this->info(Artisan::output());
                    $this->info("Migrasi berhasil dijalankan.");
                } catch (\Exception $e) {
                    $this->error("Migrasi gagal: " . $e->getMessage());
                }
            }
        } else if ($hasClasses && !$hasClassRooms) {
            $this->info('Hanya tabel classes yang ada, tidak perlu tindakan spesifik.');
        } else if (!$hasClasses && $hasClassRooms) {
            $this->info('Hanya tabel class_rooms yang ada, tidak perlu tindakan spesifik.');
        }
    }

    private function getForeignKeys($table)
    {
        $foreignKeys = [];
        $keyList = DB::select(DB::raw("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = '{$table}'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        "));

        foreach ($keyList as $key) {
            $foreignKeys[] = $key->CONSTRAINT_NAME;
        }

        return $foreignKeys;
    }

    private function checkForeignKeyConstraints()
    {
        // Periksa teacher_id di tabel class_rooms jika ada
        if (Schema::hasTable('class_rooms') && Schema::hasColumn('class_rooms', 'teacher_id')) {
            $this->info('Memeriksa foreign key teacher_id di tabel class_rooms...');
            
            $invalidTeachers = DB::table('class_rooms')
                ->whereNotNull('teacher_id')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('users')
                        ->whereColumn('users.id', 'class_rooms.teacher_id')
                        ->where('users.role', 'teacher');
                })
                ->get();
                
            if ($invalidTeachers->count() > 0) {
                $this->warn("{$invalidTeachers->count()} kelas memiliki teacher_id yang tidak valid.");
                
                if ($this->confirm('Apakah Anda ingin mengatur nilai teacher_id ini menjadi null?', true)) {
                    foreach ($invalidTeachers as $class) {
                        DB::table('class_rooms')
                            ->where('id', $class->id)
                            ->update(['teacher_id' => null]);
                    }
                    $this->info('Teacher ID yang tidak valid telah diatur menjadi null.');
                }
            } else {
                $this->info('Semua relasi teacher sudah valid.');
            }
        }
        
        // Periksa class_id di tabel users jika ada
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'class_id')) {
            $this->info('Memeriksa foreign key class_id di tabel users...');
            
            // Tentukan tabel target berdasarkan keberadaan tabel
            $targetTable = Schema::hasTable('class_rooms') ? 'class_rooms' : 'classes';
            
            $invalidClasses = DB::table('users')
                ->whereNotNull('class_id')
                ->whereNotExists(function ($query) use ($targetTable) {
                    $query->select(DB::raw(1))
                        ->from($targetTable)
                        ->whereColumn("{$targetTable}.id", 'users.class_id');
                })
                ->get();
                
            if ($invalidClasses->count() > 0) {
                $this->warn("{$invalidClasses->count()} siswa memiliki class_id yang tidak valid.");
                
                if ($this->confirm('Apakah Anda ingin mengatur nilai class_id ini menjadi null?', true)) {
                    foreach ($invalidClasses as $user) {
                        DB::table('users')
                            ->where('id', $user->id)
                            ->update(['class_id' => null]);
                    }
                    $this->info('Class ID yang tidak valid telah diatur menjadi null.');
                }
            } else {
                $this->info('Semua relasi class sudah valid.');
            }
        }
    }
}
