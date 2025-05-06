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
        // Create the many-to-many relationship table between subjects and teachers (users)
        if (!Schema::hasTable('subject_teacher')) {
            Schema::create('subject_teacher', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('subject_id');
                $table->unsignedBigInteger('user_id'); // teacher
                $table->timestamps();

                // Add unique constraint to prevent duplicate entries
                $table->unique(['subject_id', 'user_id']);
            });
        }

        // Add foreign keys in a separate step to ensure the tables exist
        Schema::table('subject_teacher', function (Blueprint $table) {
            if (Schema::hasColumn('subject_teacher', 'subject_id')) {
                $table->foreign('subject_id')
                      ->references('id')->on('subjects')
                      ->onDelete('cascade');
            }
            
            if (Schema::hasColumn('subject_teacher', 'user_id')) {
                $table->foreign('user_id')
                      ->references('id')->on('users')
                      ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_teacher');
    }
};
