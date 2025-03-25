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
            // Only add columns if they don't already exist
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('admin')->after('password');
            }
            
            // Common fields
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('role');
            }
            
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('phone_number');
            }
            
            if (!Schema::hasColumn('users', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('address');
            }
            
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['L', 'P'])->nullable()->after('birth_date');
            }
            
            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('gender');
            }
            
            // Student specific fields
            if (!Schema::hasColumn('users', 'nis')) {
                $table->string('nis')->nullable()->after('profile_photo');
            }
            
            if (!Schema::hasColumn('users', 'nisn')) {
                $table->string('nisn')->nullable()->after('nis');
            }
            
            if (!Schema::hasColumn('users', 'class') && !Schema::hasColumn('users', 'class_id')) {
                $table->string('class')->nullable()->after('nisn');
            }
            
            if (!Schema::hasColumn('users', 'academic_year')) {
                $table->string('academic_year')->nullable()->after(Schema::hasColumn('users', 'class') ? 'class' : 'nisn');
            }
            
            if (!Schema::hasColumn('users', 'parent_name')) {
                $table->string('parent_name')->nullable()->after('academic_year');
            }
            
            if (!Schema::hasColumn('users', 'parent_phone')) {
                $table->string('parent_phone')->nullable()->after('parent_name');
            }
            
            // Teacher specific fields
            if (!Schema::hasColumn('users', 'nip')) {
                $table->string('nip')->nullable()->after('parent_phone');
            }
            
            if (!Schema::hasColumn('users', 'nuptk')) {
                $table->string('nuptk')->nullable()->after('nip');
            }
            
            if (!Schema::hasColumn('users', 'subject')) {
                $table->string('subject')->nullable()->after('nuptk');
            }
            
            if (!Schema::hasColumn('users', 'position')) {
                $table->string('position')->nullable()->after('subject');
            }
            
            if (!Schema::hasColumn('users', 'join_date')) {
                $table->date('join_date')->nullable()->after('position');
            }
            
            if (!Schema::hasColumn('users', 'education_level')) {
                $table->string('education_level')->nullable()->after('join_date');
            }
            
            if (!Schema::hasColumn('users', 'education_institution')) {
                $table->string('education_institution')->nullable()->after('education_level');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We need to be careful with the down migration to avoid dropping columns that might be used by other features
        // Check if columns exist before attempting to drop them
        Schema::table('users', function (Blueprint $table) {
            $columnsToCheck = [
                'role', 'phone_number', 'address', 'birth_date', 'gender', 
                'profile_photo', 'nis', 'nisn', 'class', 'academic_year', 
                'parent_name', 'parent_phone', 'nip', 'nuptk', 'subject', 
                'position', 'join_date', 'education_level', 'education_institution'
            ];
            
            $columnsToDrop = [];
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $columnsToDrop[] = $column;
                }
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};