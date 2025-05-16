<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class FixMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-migrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix migration issues with schedules and subject_user tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration fixes...');
        
        // 1. Check if schedules table exists
        if (Schema::hasTable('schedules')) {
            $this->info('✓ Schedules table already exists');
            
            // Mark the migration as complete
            $this->markMigrationAsRun('2023_05_01_000000_create_schedules_table');
        }
        
        // 2. Create subject_user table if it doesn't exist
        if (!Schema::hasTable('subject_user')) {
            $this->info('Creating subject_user table...');
            
            Schema::create('subject_user', function ($table) {
                $table->id();
                $table->unsignedBigInteger('subject_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamps();
                
                // Foreign keys
                try {
                    $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                } catch (\Exception $e) {
                    $this->warn('Could not create foreign keys (might already exist): ' . $e->getMessage());
                }
                
                // Unique constraint
                try {
                    $table->unique(['subject_id', 'user_id']);
                } catch (\Exception $e) {
                    $this->warn('Could not create unique constraint (might already exist): ' . $e->getMessage());
                }
            });
            
            $this->markMigrationAsRun('2024_05_14_000000_create_subject_user_table');
            $this->info('✓ Created subject_user table');
        } else {
            $this->info('✓ Subject_user table already exists');
            $this->markMigrationAsRun('2024_05_14_000000_create_subject_user_table');
        }
        
        // 3. Check for old subject_teacher table and migrate data if needed
        if (Schema::hasTable('subject_teacher')) {
            $this->info('Found subject_teacher table, checking for data to migrate...');
            
            // Check columns
            $hasTeacherId = Schema::hasColumn('subject_teacher', 'teacher_id');
            $userIdColumn = $hasTeacherId ? 'teacher_id' : 'user_id';
            
            // Get count
            $count = DB::table('subject_teacher')->count();
            $this->info("Found {$count} records to migrate");
            
            if ($count > 0) {
                // Get all relations
                $relations = DB::table('subject_teacher')->get();
                
                foreach ($relations as $relation) {
                    $subjectId = $relation->subject_id;
                    $userId = $relation->$userIdColumn;
                    
                    // Check if exists in new table
                    $exists = DB::table('subject_user')
                        ->where('subject_id', $subjectId)
                        ->where('user_id', $userId)
                        ->exists();
                    
                    if (!$exists) {
                        // Insert into new table
                        DB::table('subject_user')->insert([
                            'subject_id' => $subjectId,
                            'user_id' => $userId,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
                
                $this->info("✓ Migrated {$count} relationships");
            }
        }
        
        $this->info('All migration fixes completed successfully!');
    }
    
    /**
     * Mark a migration as run in the migrations table.
     */
    protected function markMigrationAsRun($migration)
    {
        // Check if migrations table exists
        if (!Schema::hasTable('migrations')) {
            $this->error('Migrations table does not exist!');
            return;
        }
        
        // Check if already marked as run
        $exists = DB::table('migrations')
            ->where('migration', $migration)
            ->exists();
            
        if (!$exists) {
            // Add to migrations table
            DB::table('migrations')->insert([
                'migration' => $migration,
                'batch' => DB::table('migrations')->max('batch') + 1
            ]);
            
            $this->info("Marked {$migration} as completed");
        }
    }
}
