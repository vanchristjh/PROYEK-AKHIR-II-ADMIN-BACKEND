<?php

/**
 * This script helps run specific migrations without errors
 * Run using: php database/fix_migrations.php
 */

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting migration fixes...\n";

// First, check if subject_user table exists
$hasSubjectUser = \Illuminate\Support\Facades\Schema::hasTable('subject_user');
echo "subject_user table exists: " . ($hasSubjectUser ? "Yes" : "No") . "\n";

// Create it if it doesn't exist
if (!$hasSubjectUser) {
    echo "Creating subject_user table...\n";
    
    \Illuminate\Support\Facades\Schema::create('subject_user', function ($table) {
        $table->id();
        $table->unsignedBigInteger('subject_id');
        $table->unsignedBigInteger('user_id');
        $table->timestamps();
        
        $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        
        $table->unique(['subject_id', 'user_id']);
    });
    
    echo "subject_user table created successfully\n";
}

// Check if subject_teacher table exists (old table)
$hasSubjectTeacher = \Illuminate\Support\Facades\Schema::hasTable('subject_teacher');
echo "subject_teacher table exists: " . ($hasSubjectTeacher ? "Yes" : "No") . "\n";

// Migrate data from old table if it exists
if ($hasSubjectTeacher && $hasSubjectUser) {
    echo "Migrating data from subject_teacher to subject_user...\n";
    
    // Check columns in subject_teacher
    $hasTeacherId = \Illuminate\Support\Facades\Schema::hasColumn('subject_teacher', 'teacher_id');
    echo "subject_teacher has teacher_id column: " . ($hasTeacherId ? "Yes" : "No") . "\n";
    
    // Get all relations
    $relations = DB::table('subject_teacher')->get();
    echo "Found " . count($relations) . " relations to migrate\n";
    
    foreach ($relations as $relation) {
        $subjectId = $relation->subject_id;
        $teacherId = $hasTeacherId ? $relation->teacher_id : $relation->user_id;
        
        // Check if teacher exists
        $teacherExists = DB::table('users')->where('id', $teacherId)->exists();
        
        if ($teacherExists) {
            // Add to subject_user if not already there
            $exists = DB::table('subject_user')
                ->where('subject_id', $subjectId)
                ->where('user_id', $teacherId)
                ->exists();
                
            if (!$exists) {
                DB::table('subject_user')->insert([
                    'subject_id' => $subjectId,
                    'user_id' => $teacherId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                echo "Migrated relation: Subject $subjectId - Teacher $teacherId\n";
            }
        } else {
            echo "Skipped relation: Teacher $teacherId doesn't exist\n";
        }
    }
}

echo "\nMigration fixes completed!\n";
echo "You can now run 'php artisan migrate' with the --path flag to run specific migrations\n";
