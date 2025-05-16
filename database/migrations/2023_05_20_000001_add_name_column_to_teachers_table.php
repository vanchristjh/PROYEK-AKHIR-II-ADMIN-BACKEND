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
        // Check if the name column already exists in the teachers table
        if (!Schema::hasColumn('teachers', 'name')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->string('name')->nullable()->after('id');
            });
            
            // Find possible name columns that might already exist
            $columns = Schema::getColumnListing('teachers');
            $nameColumns = ['nama', 'full_name', 'first_name'];
            $sourceColumn = null;
            
            foreach ($nameColumns as $column) {
                if (in_array($column, $columns)) {
                    $sourceColumn = $column;
                    break;
                }
            }
            
            // Copy data from existing name column if found
            if ($sourceColumn) {
                DB::statement("UPDATE teachers SET name = $sourceColumn WHERE name IS NULL");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
