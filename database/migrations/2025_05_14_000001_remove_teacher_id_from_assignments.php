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
        // First, check if teacher_id exists in the assignments table
        if (Schema::hasColumn('assignments', 'teacher_id')) {
            // Remove the teacher_id column as it's not used in the model
            Schema::table('assignments', function (Blueprint $table) {
                $table->dropConstrainedForeignId('teacher_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */    public function down(): void
    {
        // Add back the teacher_id column
        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('assignments', 'teacher_id')) {
                $table->foreignId('teacher_id')->after('subject_id')->nullable()->constrained()->onDelete('cascade');
            }
        });
    }
};
