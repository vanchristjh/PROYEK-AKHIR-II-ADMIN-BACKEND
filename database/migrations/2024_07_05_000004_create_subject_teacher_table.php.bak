<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('subject_teacher')) {
            Schema::create('subject_teacher', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subject_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                
                $table->unique(['subject_id', 'user_id']);
            });
        } else {
            // The table already exists, we'll try to add foreign key constraints if needed
            try {
                Schema::table('subject_teacher', function (Blueprint $table) {
                    // Try to add foreign keys, if they fail it likely means they already exist
                    $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Foreign key might already exist, that's fine
            }
            
            try {
                Schema::table('subject_teacher', function (Blueprint $table) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Foreign key might already exist, that's fine
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't drop the table here since it might be created by another migration
    }
};
