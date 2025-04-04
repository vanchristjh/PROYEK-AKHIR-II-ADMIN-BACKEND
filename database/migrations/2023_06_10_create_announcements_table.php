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
        // Check if the table already exists before trying to create it
        if (!Schema::hasTable('announcements')) {
            Schema::create('announcements', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content');
                $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
                $table->datetime('published_at')->nullable();
                $table->string('target_audience')->default('all'); // 'all', 'students', 'teachers', etc.
                $table->boolean('is_important')->default(false);
                $table->string('image_path')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop the table in down(), as it might have been created by another migration
        // and we don't want to accidentally remove data
    }
};
