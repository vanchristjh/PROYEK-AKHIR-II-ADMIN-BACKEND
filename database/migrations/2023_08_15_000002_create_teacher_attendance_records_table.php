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
        // Skip creation if table already exists
        if (Schema::hasTable('teacher_attendance_records')) {
            return;
        }
        
        Schema::create('teacher_attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_attendance_id')->constrained('teacher_attendances')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa', 'terlambat'])->default('hadir');
            $table->text('notes')->nullable();
            $table->string('photo')->nullable(); // Store the path to the uploaded photo
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->string('location')->nullable(); // Can be used to store GPS coordinates or location name
            $table->timestamps();
            
            // Use a shorter name for the unique constraint
            $table->unique(['teacher_attendance_id', 'teacher_id'], 'teacher_attend_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_attendance_records');
    }
};
