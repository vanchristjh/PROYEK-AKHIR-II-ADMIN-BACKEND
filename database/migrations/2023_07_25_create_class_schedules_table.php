<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the table exists first
        if (!Schema::hasTable('class_schedules')) {
            Schema::create('class_schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('class_id')->nullable()->constrained('class_rooms')->onDelete('cascade');
                $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->string('subject');
                $table->string('day_of_week');
                $table->time('start_time');
                $table->time('end_time');
                $table->string('room')->nullable();
                $table->string('academic_year')->nullable();
                $table->string('semester')->nullable();
                $table->text('description')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } else {
            // The table already exists, so let's update the class_id column to use the proper foreign key
            Schema::table('class_schedules', function (Blueprint $table) {
                // First check if we have the column and it doesn't already have a foreign key constraint
                if (Schema::hasColumn('class_schedules', 'class_id')) {
                    // Try to get all foreign keys on the table
                    $foreignKeys = [];
                    try {
                        $foreignKeys = Schema::getConnection()
                            ->getDoctrineSchemaManager()
                            ->listTableForeignKeys('class_schedules');
                    } catch (\Exception $e) {
                        // Failed to get foreign keys, assume there are none for this column
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
                    
                    // If no foreign key exists for this column, add it
                    if (!$hasClassIdForeignKey) {
                        // Make sure the column is unsigned bigint first (could already be)
                        DB::statement('ALTER TABLE `class_schedules` MODIFY `class_id` BIGINT UNSIGNED NULL');
                        
                        // Add the foreign key constraint
                        $table->foreign('class_id')
                              ->references('id')
                              ->on('class_rooms')
                              ->onDelete('cascade');
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
        if (Schema::hasTable('class_schedules') && !$this->hasBeenModifiedByAnotherMigration()) {
            Schema::dropIfExists('class_schedules');
        }
    }

    /**
     * Check if another migration has modified this table.
     * This is a simple heuristic to avoid dropping tables that have been modified.
     */
    private function hasBeenModifiedByAnotherMigration(): bool
    {
        // This is a placeholder implementation
        // In a real scenario, you would have a better way to track this
        return false;
    }
};
