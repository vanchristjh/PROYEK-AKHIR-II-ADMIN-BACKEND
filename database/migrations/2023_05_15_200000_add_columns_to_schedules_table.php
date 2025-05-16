<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Add room column if it doesn't exist
            if (!Schema::hasColumn('schedules', 'room')) {
                $table->string('room', 50)->nullable()->after('end_time');
            }
            
            // Add notes column if it doesn't exist
            if (!Schema::hasColumn('schedules', 'notes')) {
                $table->text('notes')->nullable()->after('room');
            }
            
            // Add created_by column if it doesn't exist
            if (!Schema::hasColumn('schedules', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('notes');
                
                // Only add foreign key if column is added
                $table->foreign('created_by')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Drop foreign key if exists
            if (Schema::hasColumn('schedules', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
            
            // Drop other columns if they exist
            if (Schema::hasColumn('schedules', 'notes')) {
                $table->dropColumn('notes');
            }
            
            if (Schema::hasColumn('schedules', 'room')) {
                $table->dropColumn('room');
            }
        });
    }
}
