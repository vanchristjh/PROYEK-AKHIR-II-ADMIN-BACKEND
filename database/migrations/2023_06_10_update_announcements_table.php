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
        // Check if the table exists before trying to modify it
        if (Schema::hasTable('announcements')) {
            Schema::table('announcements', function (Blueprint $table) {
                // Add any missing columns from the original migration
                if (!Schema::hasColumn('announcements', 'status')) {
                    $table->enum('status', ['published', 'draft', 'archived'])->default('draft')->after('content');
                }
                
                if (!Schema::hasColumn('announcements', 'expired_at')) {
                    $table->timestamp('expired_at')->nullable()->after('published_at');
                }
                
                if (!Schema::hasColumn('announcements', 'priority')) {
                    $table->enum('priority', ['high', 'medium', 'low'])->default('medium')->after('target_audience');
                }
                
                // Rename 'author_id' to 'created_by' if it exists
                if (Schema::hasColumn('announcements', 'author_id') && !Schema::hasColumn('announcements', 'created_by')) {
                    $table->renameColumn('author_id', 'created_by');
                }
                
                // Convert 'is_important' boolean to 'priority' enum if needed
                if (Schema::hasColumn('announcements', 'is_important') && Schema::hasColumn('announcements', 'priority')) {
                    // No direct way to convert in migration, we'll handle this in a seeder or command
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse these changes as they're additive and don't break anything
    }
};
