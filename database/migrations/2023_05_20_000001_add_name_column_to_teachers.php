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
        // Check the teachers table structure
        $columns = Schema::getColumnListing('teachers');
        $nameColumns = ['name', 'full_name', 'nama', 'first_name'];
        $existingNameColumn = null;
        
        // Find if any name column already exists
        foreach ($nameColumns as $column) {
            if (in_array($column, $columns)) {
                $existingNameColumn = $column;
                break;
            }
        }
        
        // If no name column exists, add one
        if (!$existingNameColumn) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->string('name')->nullable()->after('id');
            });
            
            // If 'nama' exists but 'name' doesn't, copy data from nama to name
            if (in_array('nama', $columns)) {
                DB::statement('UPDATE teachers SET name = nama WHERE name IS NULL');
            }
        }
        // If name column exists but is null, try to populate it from other columns
        else if ($existingNameColumn !== 'name') {
            // Check if we need to add 'name' column specifically
            if (!in_array('name', $columns)) {
                Schema::table('teachers', function (Blueprint $table) {
                    $table->string('name')->nullable()->after('id');
                });
                
                // Copy data from the existing name column
                DB::statement("UPDATE teachers SET name = $existingNameColumn WHERE name IS NULL");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop the name column if it was added by this migration
        // This is a bit tricky since we don't know for sure if we added it
        // So we'll leave it to avoid potential data loss
    }
};
