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
            $table->string('role')->default('admin')->after('password');
            
            // Common fields
            $table->string('phone_number')->nullable()->after('role');
            $table->text('address')->nullable()->after('phone_number');
            $table->date('birth_date')->nullable()->after('address');
            $table->enum('gender', ['L', 'P'])->nullable()->after('birth_date');
            $table->string('profile_photo')->nullable()->after('gender');
            
            // Student specific fields
            $table->string('nis')->nullable()->after('profile_photo');
            $table->string('nisn')->nullable()->after('nis');
            $table->string('class')->nullable()->after('nisn');
            $table->string('academic_year')->nullable()->after('class');
            $table->string('parent_name')->nullable()->after('academic_year');
            $table->string('parent_phone')->nullable()->after('parent_name');
            
            // Teacher specific fields
            $table->string('nip')->nullable()->after('parent_phone');
            $table->string('nuptk')->nullable()->after('nip');
            $table->string('subject')->nullable()->after('nuptk');
            $table->string('position')->nullable()->after('subject');
            $table->date('join_date')->nullable()->after('position');
            $table->string('education_level')->nullable()->after('join_date');
            $table->string('education_institution')->nullable()->after('education_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'phone_number', 'address', 'birth_date', 'gender', 
                'profile_photo', 'nis', 'nisn', 'class', 'academic_year', 
                'parent_name', 'parent_phone', 'nip', 'nuptk', 'subject', 
                'position', 'join_date', 'education_level', 'education_institution'
            ]);
        });
    }
}; 