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
        // Disable foreign key constraints to avoid issues
        Schema::disableForeignKeyConstraints();
        
        // Get all tables in the database
        $tables = $this->getAllTables();
        
        // Process each table to fix/update foreign keys
        foreach ($tables as $tableName) {
            // Skip users and system tables
            if ($tableName === 'users' || 
                $tableName === 'migrations' || 
                $tableName === 'password_reset_tokens' || 
                $tableName === 'failed_jobs' || 
                $tableName === 'jobs' || 
                $tableName === 'job_batches') {
                continue;
            }
            
            // Check for columns that might reference users
            $columns = $this->getPotentialForeignKeyColumns($tableName);
            foreach ($columns as $columnName) {
                $this->fixForeignKeyIfNeeded($tableName, $columnName);
            }
        }
        
        // Re-enable foreign key constraints
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     * This method should run BEFORE the users table migration's down method.
     */
    public function down(): void
    {
        // Disable foreign key constraints to avoid issues
        Schema::disableForeignKeyConstraints();
        
        // Tables that might have foreign keys to users table
        $tableColumns = [
            'class_rooms' => ['teacher_id'],
            'class_schedules' => ['teacher_id', 'created_by'],
            'attendances' => ['created_by'],
            'teacher_attendances' => ['created_by'],
            'teacher_attendance_records' => ['teacher_id'],
            'teacher_subjects' => ['teacher_id'],
            'announcements' => ['created_by'],
            'academic_calendars' => ['created_by'],
            'subjects' => ['created_by'],
            'attendance_records' => ['created_by']
        ];
        
        // Drop all foreign keys referencing users table
        foreach ($tableColumns as $tableName => $columns) {
            if (Schema::hasTable($tableName)) {
                foreach ($columns as $columnName) {
                    if (Schema::hasColumn($tableName, $columnName)) {
                        $this->dropForeignKeysSafely($tableName, $columnName);
                    }
                }
            }
        }
        
        // Also check for any references to 'user_id' in any table
        $tables = $this->getAllTables();
        foreach ($tables as $tableName) {
            if (Schema::hasColumn($tableName, 'user_id')) {
                $this->dropForeignKeysSafely($tableName, 'user_id');
            }
            
            // Check other common columns that might reference users
            $columnsToCheck = ['created_by', 'updated_by', 'deleted_by', 'teacher_id', 'student_id'];
            foreach ($columnsToCheck as $columnName) {
                if (Schema::hasColumn($tableName, $columnName)) {
                    $this->dropForeignKeysSafely($tableName, $columnName);
                }
            }
        }
        
        Log::info('All foreign keys referencing users table have been dropped');
    }
    
    /**
     * Get all tables in the database
     */
    private function getAllTables(): array
    {
        $tables = [];
        $tableList = DB::select('SHOW TABLES');
        
        foreach ($tableList as $table) {
            $tables[] = reset($table);
        }
        
        return $tables;
    }
    
    /**
     * Get columns in a table that could potentially be foreign keys to users
     */
    private function getPotentialForeignKeyColumns(string $tableName): array
    {
        $columns = [];
        $potentialColumns = ['user_id', 'created_by', 'updated_by', 'deleted_by', 'teacher_id', 'student_id'];
        
        foreach ($potentialColumns as $column) {
            if (Schema::hasColumn($tableName, $column)) {
                $columns[] = $column;
            }
        }
        
        return $columns;
    }
    
    /**
     * Fix foreign key if it's missing but needed
     */
    private function fixForeignKeyIfNeeded(string $tableName, string $columnName): void
    {
        // If this column has no foreign key constraint but should reference users
        if (!$this->hasForeignKey($tableName, $columnName) && $this->shouldReferenceUsers($columnName)) {
            try {
                Schema::table($tableName, function (Blueprint $table) use ($columnName) {
                    // Determine the appropriate ON DELETE action
                    $onDelete = 'set null';
                    if (in_array($columnName, ['teacher_id', 'student_id'])) {
                        $onDelete = 'cascade';
                    }
                    
                    // Add the foreign key constraint
                    $table->foreign($columnName)
                          ->references('id')
                          ->on('users')
                          ->onDelete($onDelete);
                });
                
                Log::info("Added foreign key to {$tableName}.{$columnName} referencing users.id");
            } catch (\Exception $e) {
                Log::warning("Failed to add foreign key to {$tableName}.{$columnName}: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Determine if a column should reference the users table
     */
    private function shouldReferenceUsers(string $columnName): bool
    {
        return in_array($columnName, ['user_id', 'created_by', 'updated_by', 'deleted_by', 'teacher_id', 'student_id']);
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
            
            // Attempt direct SQL approach as a backup method
            try {
                foreach ($this->getForeignKeys($tableName, $columnName) as $foreignKey) {
                    DB::statement("ALTER TABLE `{$tableName}` DROP FOREIGN KEY `{$foreignKey}`");
                }
                Log::info("Dropped foreign keys on {$tableName}.{$columnName} using direct SQL");
            } catch (\Exception $e2) {
                Log::error("Failed to drop foreign keys on {$tableName}.{$columnName} using direct SQL: " . $e2->getMessage());
            }
        }
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
