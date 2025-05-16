<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->boolean('is_important')->default(false);
            $table->timestamp('publish_date');
            $table->enum('audience', ['all', 'administrators', 'teachers', 'students'])->default('all');
            $table->string('attachment')->nullable();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }    /**
     * Reverse the migrations.
     *
     * @return void
     */    public function down()
    {
        Schema::dropIfExists('announcements');
    }
}