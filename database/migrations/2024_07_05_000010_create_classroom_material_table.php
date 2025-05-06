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
        // First, check if the referenced tables exist, create them if they don't
        $this->ensureTablesExist();
        
        // Check if the table already exists to avoid duplicate creation
        if (!Schema::hasTable('classroom_material')) {
            Schema::create('classroom_material', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('classroom_id');
                $table->unsignedBigInteger('material_id');
                $table->timestamps();

                // Add indexes first before foreign keys
                $table->index('classroom_id');
                $table->index('material_id');
                      
                // Add a unique constraint to prevent duplicates
                $table->unique(['classroom_id', 'material_id']);
            });
            
            // Add foreign keys separately after table creation
            Schema::table('classroom_material', function (Blueprint $table) {
                $table->foreign('classroom_id')
                      ->references('id')
                      ->on('classrooms')
                      ->onDelete('cascade');
                      
                $table->foreign('material_id')
                      ->references('id')
                      ->on('materials')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Ensure necessary tables exist
     */
    private function ensureTablesExist()
    {
        // Instead of creating tables, check if they exist
        if (!Schema::hasTable('classrooms')) {
            throw new \Exception('The classrooms table does not exist. Please run the classrooms migration first.');
        }
        
        if (!Schema::hasTable('materials')) {
            throw new \Exception('The materials table does not exist. Please run the materials migration first.');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classroom_material');
    }
};
