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
        Schema::table('users', function (Blueprint $table) {
            // First add the column without constraints
            $table->unsignedBigInteger('role_id')->after('id')->nullable();
            $table->string('username')->after('name')->unique();
            $table->string('avatar')->nullable()->after('email');
        });

        // Then add the foreign key constraint if the roles table exists
        if (Schema::hasTable('roles')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('role_id')->references('id')->on('roles');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign key first if it exists
            if (Schema::hasColumn('users', 'role_id') && Schema::hasTable('roles')) {
                $table->dropForeign(['role_id']);
            }
            
            // Then drop the columns
            $table->dropColumn(['role_id', 'username', 'avatar']);
        });
    }
};
