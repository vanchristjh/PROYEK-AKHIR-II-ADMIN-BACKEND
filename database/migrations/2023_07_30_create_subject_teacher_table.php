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
        // Only create the subject_teacher pivot table
        if (!Schema::hasTable('subject_teacher')) {
            Schema::create('subject_teacher', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('subject_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamps();
                
                // Add foreign keys
                // We won't add the foreign key constraint yet, as subjects table 
                // might be created later in migration sequence
                
                $table->unique(['subject_id', 'user_id']);
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
