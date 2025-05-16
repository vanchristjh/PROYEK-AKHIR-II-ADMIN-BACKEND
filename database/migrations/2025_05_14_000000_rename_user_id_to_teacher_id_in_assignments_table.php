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
        // Only rename if the user_id column exists and teacher_id doesn't exist
        if (Schema::hasColumn('assignments', 'user_id') && !Schema::hasColumn('assignments', 'teacher_id')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->renameColumn('user_id', 'teacher_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */    public function down(): void
    {
        // Only rename if teacher_id column exists and user_id doesn't exist
        if (Schema::hasColumn('assignments', 'teacher_id') && !Schema::hasColumn('assignments', 'user_id')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->renameColumn('teacher_id', 'user_id');
            });
        }
    }
};
