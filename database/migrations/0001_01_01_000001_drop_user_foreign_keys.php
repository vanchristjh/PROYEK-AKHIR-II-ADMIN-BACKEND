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
        $this->info('Preparing to drop all foreign keys referencing users table...');
        $this->dropAllUserForeignKeys();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to do anything as this is preparatory
    }

    /**
     * Drop all foreign keys that reference the users table
     */
    private function dropAllUserForeignKeys(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();
        
        // Get all tables
        $tables = $this->getAllTables();
        
        foreach ($tables as $tableName) {
            // Skip users table itself
            if ($tableName === 'users') {
                continue;
            }
            
            // Find and drop foreign keys referencing users table
            $this->dropForeignKeysReferencingUsers($tableName);
        }
        
        $this->info('All foreign keys referencing users table have been dropped.');
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
     * Drop all foreign keys in the given table that reference the users table
     */
    private function dropForeignKeysReferencingUsers(string $tableName): void
    {
        $database = DB::connection()->getDatabaseName();
        
        // Get foreign keys that reference the users table
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = '{$database}'
              AND TABLE_NAME = '{$tableName}'
              AND REFERENCED_TABLE_NAME = 'users'
        ");
        
        if (count($foreignKeys) > 0) {
            $this->info("Found foreign keys in '{$tableName}' referencing users table.");
            
            // Drop each foreign key
            Schema::table($tableName, function (Blueprint $table) use ($foreignKeys, $tableName) {
                foreach ($foreignKeys as $key) {
                    try {
                        $table->dropForeign($key->CONSTRAINT_NAME);
                        $this->info("Dropped foreign key '{$key->CONSTRAINT_NAME}' from '{$tableName}'.");
                    } catch (\Exception $e) {
                        $this->info("Warning: Failed to drop '{$key->CONSTRAINT_NAME}' from '{$tableName}': " . $e->getMessage());
                        // Try alternative approach
                        try {
                            DB::statement("ALTER TABLE `{$tableName}` DROP FOREIGN KEY `{$key->CONSTRAINT_NAME}`");
                            $this->info("Dropped foreign key '{$key->CONSTRAINT_NAME}' from '{$tableName}' using ALTER TABLE.");
                        } catch (\Exception $e2) {
                            $this->info("Error: Could not drop '{$key->CONSTRAINT_NAME}' from '{$tableName}': " . $e2->getMessage());
                        }
                    }
                }
            });
        }
    }
    
    /**
     * Log information to console during migrations.
     */
    private function info($message): void
    {
        Log::info($message);
        if (app()->runningInConsole()) {
            echo "{$message}\n";
        }
    }
};
