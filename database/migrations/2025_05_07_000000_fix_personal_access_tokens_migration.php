<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the table exists, if not, we'll mark it as migrated
        if (Schema::hasTable('personal_access_tokens')) {
            // The table already exists - now ensure the problematic migration is marked as run
            $pendingMigrationName = '2025_05_06_164147_create_personal_access_tokens_table';
            
            // Get the latest batch number
            $latestBatch = DB::table('migrations')->max('batch');
            
            // Only insert if it doesn't already exist
            $exists = DB::table('migrations')
                ->where('migration', $pendingMigrationName)
                ->exists();
                
            if (!$exists) {
                DB::table('migrations')->insert([
                    'migration' => $pendingMigrationName,
                    'batch' => $latestBatch + 1
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the entry we added if it exists
        DB::table('migrations')
            ->where('migration', '2025_05_06_164147_create_personal_access_tokens_table')
            ->delete();
    }
};