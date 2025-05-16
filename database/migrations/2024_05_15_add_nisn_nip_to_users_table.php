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
        Schema::table('users', function (Blueprint $table) {
            // Add these columns if they don't exist
            if (!Schema::hasColumn('users', 'nisn')) {
                $table->string('nisn')->nullable();
            }
            if (!Schema::hasColumn('users', 'nip')) {
                $table->string('nip')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nisn', 'nip']);
        });
    }
};
