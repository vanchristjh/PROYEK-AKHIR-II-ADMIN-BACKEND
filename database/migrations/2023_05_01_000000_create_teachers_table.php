<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nip')->nullable()->unique();
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('subject')->nullable(); // Mata pelajaran yang diampu
            $table->string('position')->nullable(); // Jabatan, e.g., "Wali Kelas", "Kepala Sekolah"
            $table->string('photo')->nullable(); // Path to profile photo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
