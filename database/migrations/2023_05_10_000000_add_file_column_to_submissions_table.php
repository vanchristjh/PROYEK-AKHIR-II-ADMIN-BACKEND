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
        // Only run if submissions table exists
        if (Schema::hasTable('submissions')) {
            Schema::table('submissions', function (Blueprint $table) {
                if (!Schema::hasColumn('submissions', 'file')) {
                    $table->string('file')->nullable()->after('student_id');
                }
                if (!Schema::hasColumn('submissions', 'submitted_at')) {
                    $table->timestamp('submitted_at')->nullable()->after('feedback');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('submissions')) {
            Schema::table('submissions', function (Blueprint $table) {
                if (Schema::hasColumn('submissions', 'file')) {
                    $table->dropColumn('file');
                }
                if (Schema::hasColumn('submissions', 'submitted_at')) {
                    $table->dropColumn('submitted_at');
                }
            });
        }
    }
};
