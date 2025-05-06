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
        if (!Schema::hasTable('classrooms')) {
            Schema::create('classrooms', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('grade_level')->nullable();
                $table->string('academic_year')->nullable();
                $table->unsignedBigInteger('homeroom_teacher_id')->nullable();
                $table->integer('capacity')->default(30);
                $table->string('room_number')->nullable();
                $table->timestamps();
            });
            
            // Add foreign key in a separate step, only if users table exists
            if (Schema::hasTable('users')) {
                Schema::table('classrooms', function (Blueprint $table) {
                    $table->foreign('homeroom_teacher_id')
                          ->references('id')->on('users')
                          ->onDelete('set null');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
