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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('class_level')->nullable()->comment('X, XI, XII');
            $table->string('semester')->nullable()->comment('1, 2');
            $table->string('curriculum')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('credits')->default(0)->comment('SKS or other credit system');
            $table->string('subject_type')->nullable()->comment('Required, Elective, etc.');
            $table->timestamps();
        });

        // Pivot table for teachers and subjects
        Schema::create('teacher_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['teacher_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_subjects');
        Schema::dropIfExists('subjects');
    }
};
