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
        // First check if teacher_id column exists
        if (!Schema::hasColumn('assignments', 'teacher_id')) {
            Schema::table('assignments', function (Blueprint $table) {
                // Use subject_id instead of classroom_id which doesn't exist
                $table->unsignedBigInteger('teacher_id')->nullable()->after('subject_id');
            });
        }

        // Check if created_by column exists before trying to use it
        if (Schema::hasColumn('assignments', 'created_by')) {
            // Now update existing records to set teacher_id = created_by
            DB::table('assignments')->update(['teacher_id' => DB::raw('created_by')]);
        }

        // Try to add the foreign key - if it already exists, it will be ignored
        try {
            Schema::table('assignments', function (Blueprint $table) {
                $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Foreign key likely already exists - ignore the error
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // Try to drop the foreign key - if it doesn't exist, it will throw an exception
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
