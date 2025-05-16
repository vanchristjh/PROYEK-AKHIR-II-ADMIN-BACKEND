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
        Schema::table('assignments', function (Blueprint $table) {
            // Check if created_by column exists
            if (Schema::hasColumn('assignments', 'created_by')) {
                $table->string('attachment_path')->nullable()->after('created_by');
            } else {
                $table->string('attachment_path')->nullable()->after('file_path');
            }
            $table->string('attachment_name')->nullable()->after('attachment_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn(['attachment_path', 'attachment_name']);
        });
    }
};
