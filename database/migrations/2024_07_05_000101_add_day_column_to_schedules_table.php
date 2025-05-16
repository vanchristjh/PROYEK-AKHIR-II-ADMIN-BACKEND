<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'day')) {
                $table->integer('day')->comment('1=Mon, 2=Tue, 3=Wed, 4=Thu, 5=Fri, 6=Sat, 7=Sun')->after('classroom_id');
            }
            
            // Add index for performance if it doesn't exist
            if (!Schema::hasIndex('schedules', 'schedules_day_teacher_id_classroom_id_index')) {
                $table->index(['day', 'teacher_id', 'classroom_id']);
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
            if (Schema::hasColumn('schedules', 'day')) {
                $table->dropColumn('day');
            }
            
            if (Schema::hasIndex('schedules', 'schedules_day_teacher_id_classroom_id_index')) {
                $table->dropIndex('schedules_day_teacher_id_classroom_id_index');
            }
        });
    }
};