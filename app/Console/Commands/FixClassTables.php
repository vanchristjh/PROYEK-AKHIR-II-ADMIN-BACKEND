<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class FixClassTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-class-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix class table issues and migrate data if necessary';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking class tables structure...');
        
        // Check if we need to add class_id column to users table
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'class_id')) {
            $this->info('Adding class_id column to users table...');
            
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('class_id')->nullable()->constrained('class_rooms')->nullOnDelete();
            });
            
            $this->info('class_id column added to users table.');
        }
        
        // Check if both tables exist
        $hasClasses = Schema::hasTable('classes');
        $hasClassRooms = Schema::hasTable('class_rooms');
        
        if ($hasClasses && !$hasClassRooms) {
            // Rename 'classes' to 'class_rooms' if only 'classes' exists
            $this->info('Found only "classes" table. Will rename to "class_rooms".');
            
            Schema::rename('classes', 'class_rooms');
            $this->info('Table renamed successfully.');
            
            return Command::SUCCESS;
        } 
        else if ($hasClasses && $hasClassRooms) {
            // If both tables exist, migrate data from classes to class_rooms
            $this->info('Both tables exist. Will migrate data from "classes" to "class_rooms".');
            
            $classes = DB::table('classes')->get();
            $count = 0;
            
            foreach ($classes as $class) {
                // Check if a similar entry exists in class_rooms
                $exists = DB::table('class_rooms')
                    ->where('name', $class->name)
                    ->where('level', $class->level)
                    ->exists();
                
                if (!$exists) {
                    // If no similar entry exists, copy it
                    DB::table('class_rooms')->insert([
                        'name' => $class->name,
                        'level' => $class->level,
                        'type' => $class->type ?? null,
                        'capacity' => $class->capacity ?? 30,
                        'teacher_id' => $class->teacher_id ?? null,
                        'room' => $class->room ?? null,
                        'academic_year' => $class->academic_year ?? null,
                        'description' => $class->description ?? null,
                        'created_at' => $class->created_at ?? now(),
                        'updated_at' => $class->updated_at ?? now(),
                    ]);
                    $count++;
                }
            }
            
            $this->info("Migrated $count classes from 'classes' to 'class_rooms'.");
            
            // Update users table references if needed
            if (Schema::hasColumn('users', 'class_id')) {
                $this->info('Updating class_id references in users table...');
                
                $students = DB::table('users')
                    ->whereNotNull('class_id')
                    ->where('role', 'student')
                    ->get();
                
                foreach ($students as $student) {
                    // Find the corresponding class in class_rooms
                    $oldClass = DB::table('classes')->find($student->class_id);
                    
                    if ($oldClass) {
                        $newClass = DB::table('class_rooms')
                            ->where('name', $oldClass->name)
                            ->where('level', $oldClass->level)
                            ->first();
                        
                        if ($newClass) {
                            DB::table('users')
                                ->where('id', $student->id)
                                ->update(['class_id' => $newClass->id]);
                        }
                    }
                }
                
                $this->info('Updated class references in users table.');
            }
            
            $this->info('You may now drop the "classes" table if everything looks good.');
            $this->info('Run: php artisan migrate:fresh to reset your database structure.');
            
            return Command::SUCCESS;
        }
        else if (!$hasClasses && !$hasClassRooms) {
            // If neither table exists, create class_rooms table
            $this->info('No class tables found. Creating "class_rooms" table.');
            
            Schema::create('class_rooms', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('level'); // X, XI, XII
                $table->string('type')->nullable(); // IPA, IPS
                $table->integer('capacity')->default(30);
                $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('room')->nullable();
                $table->string('academic_year')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
            
            $this->info('"class_rooms" table created successfully.');
            
            return Command::SUCCESS;
        }
        
        $this->info('Class tables are already in correct structure.');
        return Command::SUCCESS;
    }
}
