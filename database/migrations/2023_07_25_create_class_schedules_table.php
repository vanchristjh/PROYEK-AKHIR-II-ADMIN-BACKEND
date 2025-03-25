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
        // First make sure the class_rooms table exists
        if (!Schema::hasTable('class_rooms')) {
            Schema::create('class_rooms', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('level'); // X, XI, XII
                $table->string('type'); // IPA, IPS
                $table->integer('capacity')->default(30);
                $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('room')->nullable();
                $table->string('academic_year')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            // Make sure to use foreignId for better compatibility
            $table->unsignedBigInteger('class_id')->nullable();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('subject');
            $table->string('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room')->nullable();
            $table->string('academic_year')->nullable();
            $table->string('semester')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Add the foreign key constraint separately
            $table->foreign('class_id')->references('id')->on('class_rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};
