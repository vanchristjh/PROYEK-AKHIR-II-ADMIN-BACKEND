<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTableIfNotExists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('classes')) {
            Schema::create('classes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('level'); // X, XI, XII
                $table->string('type'); // IPA, IPS
                $table->integer('capacity')->default(30);
                $table->string('room')->nullable();
                $table->unsignedBigInteger('teacher_id')->nullable();
                $table->string('academic_year')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
                
                // Foreign key constraint untuk MySQL
                $table->foreign('teacher_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
            });
            
            // Add class_id to users table for students if needed
            if (Schema::hasTable('users') && !Schema::hasColumn('users', 'class_id')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->unsignedBigInteger('class_id')->nullable()->after('role');
                    $table->foreign('class_id')
                          ->references('id')
                          ->on('classes')
                          ->onDelete('set null');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Tidak menghapus tabel yang sudah ada untuk mencegah kehilangan data
    }
}
