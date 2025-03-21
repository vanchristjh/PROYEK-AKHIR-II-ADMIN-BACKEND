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
        Schema::create('class_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('level'); // X, XI, XII
            $table->string('type'); // IPA, IPS
            $table->integer('capacity')->default(30);
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('room')->nullable();
            $table->string('academic_year')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Add class_id to users table for students
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'class_id')) {
                $table->foreignId('class_id')->nullable()->after('role')->constrained('class_rooms')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'class_id')) {
                $table->dropConstrainedForeignId('class_id');
            }
        });
        Schema::dropIfExists('class_rooms');
    }
};
