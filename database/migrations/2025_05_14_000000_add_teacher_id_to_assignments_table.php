<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */    public function up(): void
    {
        // Check if teacher_id column exists before adding it
        if (!Schema::hasColumn('assignments', 'teacher_id')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->unsignedBigInteger('teacher_id')->after('id');
                  // Try to add the foreign key - if it already exists, it will be ignored
                try {
                    $table->foreign('teacher_id')->references('id')->on('users');
                } catch (\Exception $e) {
                    // Foreign key likely already exists - ignore the error
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {            // Try to drop the foreign key - if it doesn't exist, it will throw an exception
            try {
                $table->dropForeign(['teacher_id']);
            } catch (\Exception $e) {
                // Foreign key likely doesn't exist - ignore the error
            }
            
            // Drop the column if it exists
            if (Schema::hasColumn('assignments', 'teacher_id')) {
                $table->dropColumn('teacher_id');
            }
        });
    }
};
