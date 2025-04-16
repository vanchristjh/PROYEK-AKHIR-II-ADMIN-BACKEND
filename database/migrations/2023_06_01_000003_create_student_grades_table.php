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
        Schema::create('student_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('grade_item_id')->constrained('grade_items')->onDelete('cascade');
            $table->decimal('score', 8, 2)->unsigned()->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('submitted_by')->constrained('users');
            $table->timestamps();
            
            // Unique constraint to prevent duplicate grades for same student/item
            $table->unique(['student_id', 'grade_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_grades');
    }
};
