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
        // Check if table already exists before creating
        if (Schema::hasTable('subject_teacher')) {
            return;
        }

        Schema::create('subject_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Ensure a teacher can only be assigned to a subject once
            $table->unique(['subject_id', 'teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Only drop if it exists and there are no other migrations using it
        if (Schema::hasTable('subject_teacher')) {
            Schema::dropIfExists('subject_teacher');
        }    }
};
