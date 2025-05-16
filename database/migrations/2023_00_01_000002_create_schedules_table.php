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
        // Skip creation if the table already exists
        if (!Schema::hasTable('schedules')) {
            Schema::create('schedules', function (Blueprint $table) {                $table->id();
                $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
                $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
                $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
                $table->string('day');
                $table->time('start_time');
                $table->time('end_time');
                $table->string('school_year');
                $table->text('notes')->nullable();
                $table->timestamps();
                
                // Add index for common queries
                $table->index(['day', 'classroom_id']);
                $table->index(['day', 'teacher_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
