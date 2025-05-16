<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */    public function up(): void
    {
        // First check if the assignments table exists
        if (!Schema::hasTable('assignments')) {
            // If assignments table doesn't exist, we need to create it first
            echo "Warning: The assignments table doesn't exist yet. Cannot create submissions table with foreign key constraint.\n";
            return;
        }
        
        if (!Schema::hasTable('submissions')) {
            Schema::create('submissions', function (Blueprint $table) {
                $table->id();
                // Add this if you want to add foreign key support after creating the table
                $table->unsignedBigInteger('assignment_id');
                $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
                $table->string('file_path')->nullable();
                $table->text('notes')->nullable();
                $table->decimal('score', 5, 2)->nullable();
                $table->text('feedback')->nullable();
                $table->timestamp('submitted_at')->useCurrent();
                $table->timestamp('graded_at')->nullable();
                $table->timestamps();

                $table->unique(['assignment_id', 'student_id']);
            });
            
            // Add foreign key constraint in a separate step after table creation
            Schema::table('submissions', function (Blueprint $table) {
                $table->foreign('assignment_id')
                    ->references('id')
                    ->on('assignments')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
