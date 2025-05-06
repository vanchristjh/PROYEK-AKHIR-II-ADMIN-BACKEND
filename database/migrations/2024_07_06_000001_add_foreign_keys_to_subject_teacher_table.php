<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the table exists
        if (Schema::hasTable('subject_teacher')) {
            // Check if the foreign keys already exist
            $foreignKeys = $this->getForeignKeys('subject_teacher');
            
            // Only add subject_id foreign key if it doesn't already exist
            if (!$this->hasForeignKey($foreignKeys, 'subject_id')) {
                try {
                    Schema::table('subject_teacher', function (Blueprint $table) {
                        $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
                    });
                } catch (\Exception $e) {
                    // If there's still an error, just log it and continue
                    \Log::info('Could not add foreign key subject_id: ' . $e->getMessage());
                }
            }
            
            // Only add user_id foreign key if it doesn't already exist
            if (!$this->hasForeignKey($foreignKeys, 'user_id')) {
                try {
                    Schema::table('subject_teacher', function (Blueprint $table) {
                        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    });
                } catch (\Exception $e) {
                    // If there's still an error, just log it and continue
                    \Log::info('Could not add foreign key user_id: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Get all foreign keys for a table
     */
    protected function getForeignKeys($table)
    {
        $foreignKeys = [];
        
        try {
            $results = DB::select("
                SELECT COLUMN_NAME, CONSTRAINT_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_NAME = ? 
                AND CONSTRAINT_NAME LIKE 'subject_teacher_%_foreign' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ", [$table]);
            
            foreach ($results as $result) {
                $foreignKeys[$result->COLUMN_NAME] = $result->CONSTRAINT_NAME;
            }
        } catch (\Exception $e) {
            // In case of error, continue with empty array
        }
        
        return $foreignKeys;
    }
    
    /**
     * Check if a foreign key exists for a column
     */
    protected function hasForeignKey($foreignKeys, $column)
    {
        return array_key_exists($column, $foreignKeys);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't take any action here since we're not guaranteed to be the ones who created these foreign keys
    }
};
