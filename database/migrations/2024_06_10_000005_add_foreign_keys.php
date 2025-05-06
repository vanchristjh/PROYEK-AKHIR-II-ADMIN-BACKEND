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
        // Make sure users table exists
        if (!Schema::hasTable('users')) {
            // Create the users table if it doesn't exist (simplified version)
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('username')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->unsignedBigInteger('role_id');
                $table->string('avatar')->nullable();
                $table->unsignedBigInteger('classroom_id')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Make sure roles table exists
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->timestamps();
            });

            // Add default roles
            DB::table('roles')->insert([
                ['name' => 'Administrator', 'slug' => 'admin', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Guru', 'slug' => 'guru', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Siswa', 'slug' => 'siswa', 'created_at' => now(), 'updated_at' => now()]
            ]);
        }
        
        // Add foreign key to classrooms table for homeroom_teacher_id
        if (Schema::hasTable('classrooms') && Schema::hasTable('users')) {
            // Check if foreign key already exists using a more compatible approach
            try {
                Schema::table('classrooms', function (Blueprint $table) {
                    $table->foreign('homeroom_teacher_id')
                          ->references('id')->on('users')
                          ->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Foreign key might already exist or other issue
                // Just continue with other operations
            }
        }
        
        // Add foreign keys to assignments table
        if (Schema::hasTable('assignments')) {
            // Classroom foreign key
            if (Schema::hasColumn('assignments', 'classroom_id') && Schema::hasTable('classrooms')) {
                try {
                    Schema::table('assignments', function (Blueprint $table) {
                        $table->foreign('classroom_id')
                              ->references('id')->on('classrooms')
                              ->onDelete('cascade');
                    });
                } catch (\Exception $e) {
                    // Continue if error (key might already exist)
                }
            }
            
            // Subject foreign key
            if (Schema::hasColumn('assignments', 'subject_id') && Schema::hasTable('subjects')) {
                try {
                    Schema::table('assignments', function (Blueprint $table) {
                        $table->foreign('subject_id')
                              ->references('id')->on('subjects')
                              ->onDelete('cascade');
                    });
                } catch (\Exception $e) {
                    // Continue if error
                }
            }
            
            // Teacher foreign key
            if (Schema::hasColumn('assignments', 'teacher_id') && Schema::hasTable('users')) {
                try {
                    Schema::table('assignments', function (Blueprint $table) {
                        $table->foreign('teacher_id')
                              ->references('id')->on('users')
                              ->onDelete('cascade');
                    });
                } catch (\Exception $e) {
                    // Continue if error
                }
            }
        }
        
        // Add foreign keys to grades table
        if (Schema::hasTable('grades')) {
            $foreignKeys = [
                'student_id' => ['users', 'cascade'],
                'teacher_id' => ['users', 'cascade'],
                'subject_id' => ['subjects', 'cascade'],
                'classroom_id' => ['classrooms', 'cascade'],
                'assignment_id' => ['assignments', 'set null'],
                'admin_id' => ['users', 'set null']
            ];
            
            foreach ($foreignKeys as $column => $config) {
                if (Schema::hasColumn('grades', $column) && Schema::hasTable($config[0])) {
                    try {
                        Schema::table('grades', function (Blueprint $table) use ($column, $config) {
                            $table->foreign($column)
                                  ->references('id')->on($config[0])
                                  ->onDelete($config[1]);
                        });
                    } catch (\Exception $e) {
                        // Continue if error
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a safer approach using try-catch blocks
        
        // Remove foreign keys from grades
        if (Schema::hasTable('grades')) {
            $columns = ['student_id', 'teacher_id', 'subject_id', 'classroom_id', 'assignment_id', 'admin_id'];
            
            foreach ($columns as $column) {
                try {
                    Schema::table('grades', function (Blueprint $table) use ($column) {
                        $table->dropForeign([$column]);
                    });
                } catch (\Exception $e) {
                    // Continue if error
                }
            }
        }
        
        // Remove foreign keys from assignments table
        if (Schema::hasTable('assignments')) {
            $columns = ['classroom_id', 'subject_id', 'teacher_id'];
            
            foreach ($columns as $column) {
                try {
                    Schema::table('assignments', function (Blueprint $table) use ($column) {
                        $table->dropForeign([$column]);
                    });
                } catch (\Exception $e) {
                    // Continue if error
                }
            }
        }
        
        // Remove foreign key from classrooms table
        if (Schema::hasTable('classrooms')) {
            try {
                Schema::table('classrooms', function (Blueprint $table) {
                    $table->dropForeign(['homeroom_teacher_id']);
                });
            } catch (\Exception $e) {
                // Continue if error
            }
        }
    }
};
