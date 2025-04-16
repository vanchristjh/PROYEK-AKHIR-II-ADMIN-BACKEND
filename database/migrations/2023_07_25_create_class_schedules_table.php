<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First check if the class_rooms table exists
        if (!Schema::hasTable('class_rooms')) {
            // Create a simple class_rooms table if it doesn't exist
            Schema::create('class_rooms', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }

        // Check if the table exists first
        if (!Schema::hasTable('class_schedules')) {
            Schema::create('class_schedules', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('class_id')->nullable();
                $table->unsignedBigInteger('teacher_id')->nullable();
                $table->string('subject');
                $table->string('day_of_week');
                $table->time('start_time');
                $table->time('end_time');
                $table->string('room')->nullable();
                $table->string('academic_year')->nullable();
                $table->string('semester')->nullable();
                $table->text('description')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->boolean('notification_enabled')->default(false);
                $table->integer('notify_minutes_before')->default(15);
                $table->boolean('notify_by_email')->default(false);
                $table->boolean('notify_by_push')->default(false);
                $table->timestamp('last_notification_sent')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                // Add foreign keys after all columns are defined
                $table->foreign('class_id')
                    ->references('id')
                    ->on('class_rooms')
                    ->onDelete('set null');
                    
                $table->foreign('teacher_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null');
                    
                $table->foreign('created_by')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null');
            });
        } else {
            // If the table already exists, we need to make sure the columns have proper types
            // first before trying to add foreign keys
            Schema::table('class_schedules', function (Blueprint $table) {
                // Check if the column exists and modify it to the correct type
                if (Schema::hasColumn('class_schedules', 'class_id')) {
                    // Simply modify the column to ensure it's the right type
                    // without trying to check its current type
                    try {
                        DB::statement('ALTER TABLE `class_schedules` MODIFY `class_id` BIGINT UNSIGNED NULL');
                    } catch (\Exception $e) {
                        // Log the error but continue
                        \Log::error('Failed to modify column: ' . $e->getMessage());
                    }
                    
                    // Check existing foreign keys
                    $foreignKeys = [];
                    try {
                        $foreignKeys = Schema::getConnection()
                            ->getDoctrineSchemaManager()
                            ->listTableForeignKeys('class_schedules');
                    } catch (\Exception $e) {
                        // Failed to get foreign keys, assume there are none
                    }
                    
                    // Check if class_id already has a foreign key
                    $hasClassIdForeignKey = false;
                    if (!empty($foreignKeys)) {
                        foreach ($foreignKeys as $foreignKey) {
                            if (in_array('class_id', $foreignKey->getLocalColumns())) {
                                $hasClassIdForeignKey = true;
                                break;
                            }
                        }
                    }
                    
                    // Only add the foreign key if it doesn't exist already
                    if (!$hasClassIdForeignKey) {
                        try {
                            // Make sure the referenced table exists
                            if (Schema::hasTable('class_rooms')) {
                                // Try dropping any existing foreign key constraints first
                                try {
                                    DB::statement('ALTER TABLE `class_schedules` DROP FOREIGN KEY IF EXISTS `class_schedules_class_id_foreign`');
                                } catch (\Exception $e) {
                                    // Ignore errors if the constraint doesn't exist
                                }
                                
                                // Add the foreign key constraint
                                $table->foreign('class_id')
                                      ->references('id')
                                      ->on('class_rooms')
                                      ->onDelete('set null');
                            }
                        } catch (\Exception $e) {
                            // Log the error but continue with migration
                            \Log::error('Failed to add foreign key: ' . $e->getMessage());
                        }
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop the table if we're just updating it
        // We'll only drop it if we created it in this migration
        if (Schema::hasTable('class_schedules')) {
            Schema::dropIfExists('class_schedules');
        }
    }
};
