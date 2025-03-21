<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixDatabaseConstraints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-database-constraints';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix foreign key constraints in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Beginning database constraint fix process...');
        
        // Get the database connection
        $connection = config('database.default');
        
        // If SQLite, enable foreign keys
        if ($connection === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
            $this->info('Enabled foreign keys in SQLite.');
        }
        
        // Check if classes and class_rooms tables both exist (potential conflict)
        $hasClasses = Schema::hasTable('classes');
        $hasClassRooms = Schema::hasTable('class_rooms');
        
        if ($hasClasses && $hasClassRooms) {
            $this->warn('Both classes and class_rooms tables exist. This may cause confusion.');
            
            if ($this->confirm('Do you want to migrate data from classes to class_rooms and drop the classes table?')) {
                // Copy data from classes to class_rooms
                $classes = DB::table('classes')->get();
                
                foreach ($classes as $class) {
                    // Check if teacher_id exists in users table
                    if ($class->teacher_id) {
                        $teacherExists = DB::table('users')
                            ->where('id', $class->teacher_id)
                            ->where('role', 'teacher')
                            ->exists();
                            
                        if (!$teacherExists) {
                            $this->warn("Teacher with ID {$class->teacher_id} does not exist. Setting to null.");
                            $class->teacher_id = null;
                        }
                    }
                    
                    // Insert into class_rooms
                    DB::table('class_rooms')->insertOrIgnore([
                        'name' => $class->name,
                        'level' => $class->level,
                        'type' => $class->type,
                        'capacity' => $class->capacity,
                        'teacher_id' => $class->teacher_id,
                        'room' => $class->room,
                        'academic_year' => $class->academic_year,
                        'description' => $class->description,
                        'created_at' => $class->created_at,
                        'updated_at' => $class->updated_at,
                    ]);
                }
                
                // Update users table to use class_rooms instead of classes
                if (Schema::hasColumn('users', 'class_id')) {
                    $this->info('Updating student class references...');
                    
                    $students = DB::table('users')
                        ->whereNotNull('class_id')
                        ->get();
                        
                    foreach ($students as $student) {
                        // Find matching class in class_rooms
                        $originalClass = DB::table('classes')
                            ->where('id', $student->class_id)
                            ->first();
                            
                        if ($originalClass) {
                            // Find matching class in class_rooms
                            $newClass = DB::table('class_rooms')
                                ->where('name', $originalClass->name)
                                ->where('level', $originalClass->level)
                                ->where('type', $originalClass->type)
                                ->first();
                                
                            if ($newClass) {
                                DB::table('users')
                                    ->where('id', $student->id)
                                    ->update(['class_id' => $newClass->id]);
                            }
                        }
                    }
                }
                
                // Drop the classes table
                Schema::dropIfExists('classes');
                $this->info('Dropped classes table after migrating data.');
            }
        }
        
        // Verify teacher associations in class_rooms
        if ($hasClassRooms) {
            $this->info('Checking teacher associations in class_rooms table...');
            
            $invalidTeachers = DB::table('class_rooms')
                ->whereNotNull('teacher_id')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('users')
                        ->whereColumn('users.id', 'class_rooms.teacher_id')
                        ->where('users.role', 'teacher');
                })
                ->get();
                
            if ($invalidTeachers->count() > 0) {
                $this->warn("{$invalidTeachers->count()} classes have invalid teacher IDs.");
                
                if ($this->confirm('Do you want to set these teacher_id values to null?')) {
                    foreach ($invalidTeachers as $class) {
                        DB::table('class_rooms')
                            ->where('id', $class->id)
                            ->update(['teacher_id' => null]);
                    }
                    $this->info('Invalid teacher IDs have been set to null.');
                }
            } else {
                $this->info('All teacher associations are valid.');
            }
        }
        
        $this->info('Database constraint fix process completed!');
        
        return Command::SUCCESS;
    }
}
