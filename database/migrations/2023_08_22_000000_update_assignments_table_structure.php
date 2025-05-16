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
        Schema::table('assignments', function (Blueprint $table) {
            // Check if these columns don't exist before adding them
            if (!Schema::hasColumn('assignments', 'file_path')) {
                $table->string('file_path')->nullable();
            }
            if (!Schema::hasColumn('assignments', 'file_name')) {
                $table->string('file_name')->nullable();
            }
            if (!Schema::hasColumn('assignments', 'max_score')) {
                $table->unsignedInteger('max_score')->default(100);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'file_name', 'max_score']);
        });
    }
};
