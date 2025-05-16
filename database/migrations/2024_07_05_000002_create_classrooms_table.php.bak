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
                $table->string('grade_level');
                $table->string('academic_year');
                $table->foreignId('homeroom_teacher_id')->nullable()->constrained('users');
                $table->integer('capacity')->default(30);
                $table->string('room_number')->nullable();
                $table->timestamps();
            });
        }

        // Add classroom_id to users table for students
        // Only add classroom_id if it doesn't exist already
        if (!Schema::hasColumn('users', 'classroom_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('classroom_id')->nullable()->after('role_id')->constrained();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'classroom_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['classroom_id']);
                $table->dropColumn('classroom_id');
            });
        }
        
        Schema::dropIfExists('classrooms');
    }
};
