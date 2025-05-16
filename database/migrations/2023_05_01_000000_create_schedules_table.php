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
        // Check if table already exists before trying to create it
        if (!Schema::hasTable('schedules')) {
            Schema::create('schedules', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('classroom_id');
                $table->unsignedBigInteger('subject_id');
                $table->unsignedBigInteger('teacher_id');
                $table->string('day');
                $table->time('start_time');
                $table->time('end_time');
                $table->text('notes')->nullable();
                $table->timestamps();
                
                // Foreign keys
                $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('cascade');
                $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
                $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
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
