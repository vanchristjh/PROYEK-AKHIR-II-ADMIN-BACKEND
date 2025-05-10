<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('grades')) {
            return;
        }
        
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->string('semester');
            $table->string('academic_year');
            $table->decimal('assignment_score', 5, 2)->nullable();
            $table->decimal('midterm_score', 5, 2)->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
            $table->decimal('total_score', 5, 2)->nullable();
            $table->char('grade', 2)->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
            
            // Ensure a student can only have one grade per subject per semester/year
            $table->unique(['student_id', 'subject_id', 'semester', 'academic_year'], 'unique_student_subject_semester_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Only drop if it exists
        if (Schema::hasTable('grades')) {
            Schema::dropIfExists('grades');
        }
    }
};
