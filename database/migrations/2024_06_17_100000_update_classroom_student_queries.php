<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create a view to help with student queries using the role_user table
        DB::statement('
            CREATE OR REPLACE VIEW student_users AS
            SELECT u.*
            FROM users u
            INNER JOIN role_user ru ON u.id = ru.user_id
            INNER JOIN roles r ON ru.role_id = r.id
            WHERE r.name = "siswa" OR r.name = "student"
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS student_users');
    }
};
