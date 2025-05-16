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
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
        });
        
        // Update existing roles with slug values
        DB::table('roles')->where('name', 'admin')->update(['slug' => 'admin']);
        DB::table('roles')->where('name', 'teacher')->update(['slug' => 'guru']);
        DB::table('roles')->where('name', 'student')->update(['slug' => 'siswa']);
        DB::table('roles')->where('name', 'parent')->update(['slug' => 'parent']);
        
        // Now add the unique constraint
        Schema::table('roles', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};
