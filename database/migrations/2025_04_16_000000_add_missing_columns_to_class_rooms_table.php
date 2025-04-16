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
        if (Schema::hasTable('class_rooms')) {
            Schema::table('class_rooms', function (Blueprint $table) {
                if (!Schema::hasColumn('class_rooms', 'level')) {
                    $table->string('level')->after('name')->comment('X, XI, XII');
                }
                
                if (!Schema::hasColumn('class_rooms', 'type')) {
                    $table->string('type')->nullable()->after('level')->comment('IPA, IPS, etc');
                }
                
                if (!Schema::hasColumn('class_rooms', 'capacity')) {
                    $table->integer('capacity')->default(30)->after('type');
                }
                
                if (!Schema::hasColumn('class_rooms', 'room')) {
                    $table->string('room')->nullable()->after('capacity');
                }
                
                if (!Schema::hasColumn('class_rooms', 'academic_year')) {
                    $table->string('academic_year')->nullable()->after('room');
                }
                
                if (!Schema::hasColumn('class_rooms', 'description')) {
                    $table->text('description')->nullable()->after('academic_year');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't remove columns in down migration to prevent data loss
    }
};
