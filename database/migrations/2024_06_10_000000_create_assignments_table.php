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
        // Don't create if it already exists
        if (!Schema::hasTable('assignments')) {
            Schema::create('assignments', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->unsignedBigInteger('classroom_id');
                $table->unsignedBigInteger('subject_id');
                $table->unsignedBigInteger('teacher_id');
                $table->string('attachment_path')->nullable();
                $table->timestamp('deadline')->nullable();
                $table->decimal('max_score', 5, 2)->default(100.00);
                $table->timestamps();
            });
            
            // Add foreign keys in a separate step, only if referenced tables exist
            if (Schema::hasTable('classrooms')) {
                Schema::table('assignments', function (Blueprint $table) {
                    $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('cascade');
                });
            }
            
            if (Schema::hasTable('subjects')) {
                Schema::table('assignments', function (Blueprint $table) {
                    $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
                });
            }
            
            if (Schema::hasTable('users')) {
                Schema::table('assignments', function (Blueprint $table) {
                    $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
