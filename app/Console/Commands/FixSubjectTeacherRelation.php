<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixSubjectTeacherRelation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-subject-teacher-relation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix the relationship between subjects and teachers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix subject-teacher relationship...');

        // Check if subject_teacher table exists (old table name)
        if (Schema::hasTable('subject_teacher')) {
            $this->info('Found old subject_teacher table');
            
            // Check if subject_user table exists (new table name)
            if (!Schema::hasTable('subject_user')) {
                // Create the new table
                Schema::create('subject_user', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('subject_id');
                    $table->unsignedBigInteger('user_id');
                    $table->timestamps();
                    
                    $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    
                    $table->unique(['subject_id', 'user_id']);
                });
                
                $this->info('Created subject_user table');
            }
            
            // Check if the old table has data that needs to be migrated
            $oldRelations = DB::table('subject_teacher')->get();
            
            if ($oldRelations->count() > 0) {
                $this->info("Found {$oldRelations->count()} relations to migrate");
                
                // Check if subject_teacher has teacher_id column
                $hasTeacherId = Schema::hasColumn('subject_teacher', 'teacher_id');
                
                foreach ($oldRelations as $relation) {
                    // Map the correct column names
                    $subjectId = $relation->subject_id;
                    $teacherId = $hasTeacherId ? $relation->teacher_id : $relation->user_id;
                    
                    // Check if the teacher exists as a user
                    $teacherExists = DB::table('users')->where('id', $teacherId)->exists();
                    
                    if ($teacherExists) {
                        // Insert into the new table
                        DB::table('subject_user')->insertOrIgnore([
                            'subject_id' => $subjectId,
                            'user_id' => $teacherId,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        
                        $this->info("Migrated relation: Subject ID {$subjectId} - Teacher ID {$teacherId}");
                    } else {
                        $this->warn("Skipped relation: Teacher ID {$teacherId} doesn't exist");
                    }
                }
                
                $this->info('Finished migrating relations');
            } else {
                $this->info('No data to migrate from old table');
            }
        } else {
            $this->info('Old subject_teacher table does not exist, checking for subject_user table');
            
            // Check if subject_user table exists
            if (!Schema::hasTable('subject_user')) {
                // Create the new table
                Schema::create('subject_user', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('subject_id');
                    $table->unsignedBigInteger('user_id');
                    $table->timestamps();
                    
                    $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    
                    $table->unique(['subject_id', 'user_id']);
                });
                
                $this->info('Created subject_user table from scratch');
            } else {
                $this->info('subject_user table already exists');
            }
        }
        
        // Update the Subject model if needed
        $modelPath = app_path('Models/Subject.php');
        if (file_exists($modelPath)) {
            $content = file_get_contents($modelPath);
            
            // Check if the relationship is defined incorrectly
            if (strpos($content, 'belongsToMany(User::class, \'subject_teacher\'') !== false) {
                $this->info('Updating Subject model relationship...');
                
                // Replace the relationship definition
                $content = preg_replace(
                    '/belongsToMany\(\s*User::class\s*,\s*\'subject_teacher\'\s*,\s*\'subject_id\'\s*,\s*\'teacher_id\'\s*\)/',
                    'belongsToMany(User::class, \'subject_user\', \'subject_id\', \'user_id\')',
                    $content
                );
                
                file_put_contents($modelPath, $content);
                $this->info('Subject model updated successfully');
            } else {
                $this->info('Subject model seems to have the correct relationship');
            }
        }
        
        $this->info('Relationship fix completed!');
    }
}
