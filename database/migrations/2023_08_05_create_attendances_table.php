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
        if (!Schema::hasTable('attendances')) {
            Schema::create('attendances', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('class_id');
                $table->unsignedBigInteger('subject_id');
                $table->date('date');
                $table->time('start_time');
                $table->time('end_time');
                $table->text('notes')->nullable();
                $table->boolean('is_completed')->default(false);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                // Add index for faster queries
                $table->index(['date', 'class_id']);
                $table->index(['subject_id', 'class_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
