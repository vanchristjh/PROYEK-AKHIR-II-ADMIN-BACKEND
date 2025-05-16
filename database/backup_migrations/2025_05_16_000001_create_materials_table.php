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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('publish_date')->useCurrent();
            $table->boolean('is_active')->default(true);
            $table->json('audience')->nullable(); // For storing classroom IDs 
            $table->timestamp('expiry_date')->nullable();
            $table->timestamps();
        });

        // Create pivot table for many-to-many relation between materials and classrooms
        if (!Schema::hasTable('classroom_material')) {
            Schema::create('classroom_material', function (Blueprint $table) {
                $table->id();
                $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
                $table->foreignId('material_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom_material');
        Schema::dropIfExists('materials');
    }
};
