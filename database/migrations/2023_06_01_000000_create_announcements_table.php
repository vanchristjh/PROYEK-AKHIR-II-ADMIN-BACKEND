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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('status', ['published', 'draft', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->enum('target_audience', ['all', 'students', 'teachers', 'staff'])->default('all');
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
