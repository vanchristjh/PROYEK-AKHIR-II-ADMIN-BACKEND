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
        // First check if the table exists
        if (Schema::hasTable('subject_teacher')) {
            // Check if the teacher_id column is missing
            if (!Schema::hasColumn('subject_teacher', 'teacher_id')) {
                // If we have user_id column instead of teacher_id
                if (Schema::hasColumn('subject_teacher', 'user_id')) {
                    // Rename the column
                    Schema::table('subject_teacher', function (Blueprint $table) {
                        $table->renameColumn('user_id', 'teacher_id');
                    });
                } else {
                    // Add the teacher_id column
                    Schema::table('subject_teacher', function (Blueprint $table) {
                        $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
                    });
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need for down migration as we're fixing an issue
    }
};
