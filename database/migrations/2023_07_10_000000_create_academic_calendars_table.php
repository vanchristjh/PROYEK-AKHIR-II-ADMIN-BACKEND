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
        Schema::create('academic_calendars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('location')->nullable();
            $table->enum('event_type', ['academic', 'exam', 'holiday', 'meeting', 'extracurricular', 'other'])->default('academic');
            $table->boolean('is_important')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('academic_year')->nullable();
            $table->enum('semester', ['1', '2'])->nullable();
            $table->enum('target_audience', ['all', 'students', 'teachers', 'staff'])->default('all');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_calendars');
    }
};
