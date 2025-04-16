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
        Schema::table('academic_calendars', function (Blueprint $table) {
            // Add is_holiday and is_exam columns if they don't exist
            if (!Schema::hasColumn('academic_calendars', 'is_holiday')) {
                $table->boolean('is_holiday')->default(false);
            }
            
            if (!Schema::hasColumn('academic_calendars', 'is_exam')) {
                $table->boolean('is_exam')->default(false);
            }
            
            // Add color column without specifying position
            if (!Schema::hasColumn('academic_calendars', 'color')) {
                $table->string('color')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_calendars', function (Blueprint $table) {
            $table->dropColumn('color');
            // Optionally also drop the other columns if needed
            // $table->dropColumn('is_exam');
            // $table->dropColumn('is_holiday');
        });
    }
};
