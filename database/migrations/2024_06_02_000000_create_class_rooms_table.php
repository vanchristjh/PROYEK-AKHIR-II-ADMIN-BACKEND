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
     */
    public function up(): void
    {
        if (!Schema::hasTable('class_rooms')) {
            Schema::create('class_rooms', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('level'); // X, XI, XII
                $table->string('type'); // IPA, IPS
                $table->integer('capacity')->default(30);
                $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('room')->nullable();
                $table->string('academic_year')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Add class_id to users table for students if it doesn't exist yet
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'class_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('class_id')->nullable()->after('role')->constrained('class_rooms')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First remove foreign key constraints from users table
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'class_id')) {
            $this->dropForeignKeysSafely('users', 'class_id');
        }
        
        // Check for foreign keys in other tables that might reference class_rooms
        $tables = ['class_schedules', 'attendances'];
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'class_id')) {
                $this->dropForeignKeysSafely($tableName, 'class_id');
            }
        }
        
        // Now it's safe to drop the class_rooms table
        Schema::dropIfExists('class_rooms');
    }
    
    /**
     * Drop foreign keys safely by finding their actual names first
     */
    private function dropForeignKeysSafely($tableName, $columnName)
    {
        try {
            // Get actual foreign key names from the database
            $foreignKeys = $this->getForeignKeys($tableName, $columnName);
            
            if (!empty($foreignKeys)) {
                Schema::table($tableName, function (Blueprint $table) use ($foreignKeys) {
                    foreach ($foreignKeys as $foreignKey) {
                        $table->dropForeign($foreignKey);
                    }
                });
                Log::info("Dropped foreign keys on {$tableName}.{$columnName}: " . implode(', ', $foreignKeys));
            } else {
                Log::info("No foreign keys found on {$tableName}.{$columnName}");
            }
        } catch (\Exception $e) {
            Log::warning("Error dropping foreign keys on {$tableName}: " . $e->getMessage());
        }
    }
    
    /**
     * Get the actual foreign key constraint names for a column
     */
    private function getForeignKeys($tableName, $columnName) 
    {
        $database = DB::connection()->getDatabaseName();
        
        $keys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = '{$database}'
              AND TABLE_NAME = '{$tableName}'
              AND COLUMN_NAME = '{$columnName}'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        
        return array_map(function($key) {
            return $key->CONSTRAINT_NAME;
        }, $keys);
    }
};
