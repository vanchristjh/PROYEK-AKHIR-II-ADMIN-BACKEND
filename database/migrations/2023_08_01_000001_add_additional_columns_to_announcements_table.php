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
        // Only add columns that weren't in the original announcements table
        // Replace this with actual columns from the duplicate migration if they're needed
        Schema::table('announcements', function (Blueprint $table) {
            // $table->string('example_column')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            // $table->dropColumn('example_column');
        });
    }
};
