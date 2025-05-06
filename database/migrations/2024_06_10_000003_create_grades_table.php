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
        if (!Schema::hasTable('grades')) {
            Schema::create('grades', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('teacher_id');
                $table->unsignedBigInteger('subject_id');
                $table->unsignedBigInteger('classroom_id');
                $table->unsignedBigInteger('assignment_id')->nullable();
                $table->decimal('score', 5, 2);
                $table->decimal('max_score', 5, 2)->default(100.00);
                $table->string('type')->default('assignment');
                $table->text('feedback')->nullable();
                $table->string('semester')->nullable();
                $table->string('academic_year')->nullable();
                $table->boolean('modified_by_admin')->default(false);
                $table->unsignedBigInteger('admin_id')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
