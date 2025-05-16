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
        if (!Schema::hasTable('subject_teacher')) {
            Schema::create('subject_teacher', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
                $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                
                // Create a unique constraint
                $table->unique(['subject_id', 'teacher_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_teacher');
    }
};
