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
        // Check if the announcements table exists and if expiry_date column doesn't exist
        if (Schema::hasTable('announcements') && !Schema::hasColumn('announcements', 'expiry_date')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->timestamp('expiry_date')->nullable()->after('publish_date');
            });
        }
        
        // Check if announcements table exists but doesn't have attachment_path column
        // Some instances might use attachment_path instead of attachment
        if (Schema::hasTable('announcements') && !Schema::hasColumn('announcements', 'attachment_path') && !Schema::hasColumn('announcements', 'attachment')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->string('attachment_path')->nullable()->after('audience');
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
        if (Schema::hasTable('announcements') && Schema::hasColumn('announcements', 'expiry_date')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->dropColumn('expiry_date');
            });
        }
        
        if (Schema::hasTable('announcements') && Schema::hasColumn('announcements', 'attachment_path')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->dropColumn('attachment_path');
            });
        }
    }
};