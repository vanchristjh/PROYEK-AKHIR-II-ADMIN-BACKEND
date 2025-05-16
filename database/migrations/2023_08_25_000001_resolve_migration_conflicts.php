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
        // Clean up potentially duplicate migration records
        $migrations = ['2023_08_15_010000_create_assignments_table'];
        
        foreach ($migrations as $migration) {
            $count = DB::table('migrations')
                ->where('migration', $migration)
                ->count();
                
            if ($count > 1) {
                // Keep only the first occurrence
                $first = DB::table('migrations')
                    ->where('migration', $migration)
                    ->orderBy('id')
                    ->first();
                
                DB::table('migrations')
                    ->where('migration', $migration)
                    ->where('id', '!=', $first->id)
                    ->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to revert
    }
};
