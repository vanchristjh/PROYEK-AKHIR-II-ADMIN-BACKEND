<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            // Add the missing notification columns if they don't exist
            if (!Schema::hasColumn('class_schedules', 'notification_enabled')) {
                $table->boolean('notification_enabled')->default(false)->after('is_active');
            }
            
            if (!Schema::hasColumn('class_schedules', 'notify_minutes_before')) {
                $table->integer('notify_minutes_before')->default(15)->after('notification_enabled');
            }
            
            if (!Schema::hasColumn('class_schedules', 'notify_by_email')) {
                $table->boolean('notify_by_email')->default(false)->after('notify_minutes_before');
            }
            
            if (!Schema::hasColumn('class_schedules', 'notify_by_push')) {
                $table->boolean('notify_by_push')->default(true)->after('notify_by_email');
            }
            
            if (!Schema::hasColumn('class_schedules', 'last_notification_sent')) {
                $table->timestamp('last_notification_sent')->nullable()->after('notify_by_push');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            // Remove the notification columns if they exist
            if (Schema::hasColumn('class_schedules', 'notification_enabled')) {
                $table->dropColumn('notification_enabled');
            }
            
            if (Schema::hasColumn('class_schedules', 'notify_minutes_before')) {
                $table->dropColumn('notify_minutes_before');
            }
            
            if (Schema::hasColumn('class_schedules', 'notify_by_email')) {
                $table->dropColumn('notify_by_email');
            }
            
            if (Schema::hasColumn('class_schedules', 'notify_by_push')) {
                $table->dropColumn('notify_by_push');
            }
            
            if (Schema::hasColumn('class_schedules', 'last_notification_sent')) {
                $table->dropColumn('last_notification_sent');
            }
        });
    }
};
