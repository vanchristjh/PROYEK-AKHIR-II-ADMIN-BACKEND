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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('teacher_id')->constrained('users');
            
            // Check if subjects table exists before creating foreign key
            if (Schema::hasTable('subjects')) {
                $table->foreignId('subject_id')->constrained('subjects');
            } else {
                $table->unsignedBigInteger('subject_id');
            }
            
            // Check if classrooms table exists before creating foreign key
            if (Schema::hasTable('classrooms')) {
                $table->foreignId('classroom_id')->constrained('classrooms');
            } else {
                $table->unsignedBigInteger('classroom_id');
            }
            
            // Make the assignment_id nullable and only add constraint if table exists
            $table->unsignedBigInteger('assignment_id')->nullable();
            if (Schema::hasTable('assignments')) {
                $table->foreign('assignment_id')->references('id')->on('assignments');
            }
            
            $table->decimal('score', 5, 2);
            $table->decimal('max_score', 5, 2)->default(100.00);
            $table->string('type')->default('assignment'); // assignment, quiz, exam, etc.
            $table->text('feedback')->nullable();
            $table->string('semester')->nullable();
            $table->string('academic_year')->nullable();
            $table->boolean('modified_by_admin')->default(false);
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
