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
    {        // Make sure teacher_id exists
        if (!Schema::hasColumn('assignments', 'teacher_id')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->unsignedBigInteger('teacher_id')->nullable()->after('subject_id');
            });
        }

        // Update teacher_id with created_by for all rows where teacher_id is null if created_by exists
        if (Schema::hasColumn('assignments', 'created_by')) {
            DB::statement('UPDATE assignments SET teacher_id = created_by WHERE teacher_id IS NULL');
        }

        // Add foreign key constraint if it doesn't already exist        // Add foreign key if it doesn't already exist
        if (!$this->hasConstraint('assignments', 'assignments_teacher_id_foreign')) {
            // Just add the foreign key without trying to drop it first
            Schema::table('assignments', function (Blueprint $table) {
                // Add the foreign key
                $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this as it's a data fix
    }

    /**
     * Check if a constraint exists on a table
     */
    private function hasConstraint($table, $constraintName) 
    {
        $constraints = DB::select(
            "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
             WHERE TABLE_SCHEMA = DATABASE() 
             AND TABLE_NAME = ? 
             AND CONSTRAINT_NAME = ?",
            [$table, $constraintName]
        );
        
        return count($constraints) > 0;
    }
};
