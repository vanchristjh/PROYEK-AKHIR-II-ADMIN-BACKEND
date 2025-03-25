<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add foreign keys with separate try/catch blocks so if one fails, others can still be attempted

        // 1. Check if class_rooms table exists and has records
        if (Schema::hasTable('class_rooms') && DB::table('class_rooms')->count() > 0) {
            try {
                Schema::table('attendances', function (Blueprint $table) {
                    if (!$this->hasForeignKey('attendances', 'class_id')) {
                        $table->foreign('class_id')
                              ->references('id')
                              ->on('class_rooms')
                              ->onDelete('cascade');
                        Log::info('Added foreign key: attendances.class_id -> class_rooms.id');
                    }
                });
            } catch (\Exception $e) {
                Log::error('Failed to add foreign key attendances.class_id: ' . $e->getMessage());
            }
        } else {
            Log::warning('class_rooms table not found or empty, skipping foreign key');
        }

        // 2. Check if subjects table exists and has records
        if (Schema::hasTable('subjects') && DB::table('subjects')->count() > 0) {
            try {
                Schema::table('attendances', function (Blueprint $table) {
                    if (!$this->hasForeignKey('attendances', 'subject_id')) {
                        $table->foreign('subject_id')
                              ->references('id')
                              ->on('subjects')
                              ->onDelete('cascade');
                        Log::info('Added foreign key: attendances.subject_id -> subjects.id');
                    }
                });
            } catch (\Exception $e) {
                Log::error('Failed to add foreign key attendances.subject_id: ' . $e->getMessage());
            }
        } else {
            Log::warning('subjects table not found or empty, skipping foreign key');
        }

        // 3. Check if users table exists
        if (Schema::hasTable('users')) {
            try {
                Schema::table('attendances', function (Blueprint $table) {
                    if (!$this->hasForeignKey('attendances', 'created_by')) {
                        $table->foreign('created_by')
                              ->references('id')
                              ->on('users')
                              ->onDelete('set null');
                        Log::info('Added foreign key: attendances.created_by -> users.id');
                    }
                });
            } catch (\Exception $e) {
                Log::error('Failed to add foreign key attendances.created_by: ' . $e->getMessage());
            }
        }

        // 4. Add foreign keys to attendance_records
        if (Schema::hasTable('attendances') && Schema::hasTable('attendance_records')) {
            try {
                Schema::table('attendance_records', function (Blueprint $table) {
                    if (!$this->hasForeignKey('attendance_records', 'attendance_id')) {
                        $table->foreign('attendance_id')
                              ->references('id')
                              ->on('attendances')
                              ->onDelete('cascade');
                        Log::info('Added foreign key: attendance_records.attendance_id -> attendances.id');
                    }
                });
            } catch (\Exception $e) {
                Log::error('Failed to add foreign key attendance_records.attendance_id: ' . $e->getMessage());
            }
        }

        // 5. Check if students table exists
        if (Schema::hasTable('students') && Schema::hasTable('attendance_records')) {
            try {
                Schema::table('attendance_records', function (Blueprint $table) {
                    if (!$this->hasForeignKey('attendance_records', 'student_id')) {
                        $table->foreign('student_id')
                              ->references('id')
                              ->on('students')
                              ->onDelete('cascade');
                        Log::info('Added foreign key: attendance_records.student_id -> students.id');
                    }
                });
            } catch (\Exception $e) {
                Log::error('Failed to add foreign key attendance_records.student_id: ' . $e->getMessage());
            }
        } else {
            Log::warning('students table not found, skipping foreign key');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop foreign keys with separate try/catch blocks
        $this->safelyDropForeignKey('attendance_records', 'attendance_records_attendance_id_foreign');
        $this->safelyDropForeignKey('attendance_records', 'attendance_records_student_id_foreign');
        $this->safelyDropForeignKey('attendances', 'attendances_class_id_foreign');
        $this->safelyDropForeignKey('attendances', 'attendances_subject_id_foreign');
        $this->safelyDropForeignKey('attendances', 'attendances_created_by_foreign');
    }

    /**
     * Check if a foreign key exists on a table.
     */
    private function hasForeignKey($table, $column)
    {
        try {
            $database = DB::connection()->getDatabaseName();
            $keyExists = DB::select("
                SELECT 1
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = '{$database}'
                  AND TABLE_NAME = '{$table}'
                  AND COLUMN_NAME = '{$column}'
                  AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            return count($keyExists) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Safely drop a foreign key if it exists.
     */
    private function safelyDropForeignKey($table, $foreignKey)
    {
        try {
            Schema::table($table, function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign([$foreignKey]);
            });
            Log::info("Dropped foreign key: {$foreignKey}");
        } catch (\Exception $e) {
            Log::warning("Failed to drop foreign key {$foreignKey}: " . $e->getMessage());
        }
    }
};
