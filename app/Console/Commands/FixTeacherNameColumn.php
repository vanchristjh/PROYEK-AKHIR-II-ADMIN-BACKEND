<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixTeacherNameColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:teacher-name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix the name column issue in the teachers table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking teachers table structure...');
        
        // Check if teachers table exists
        if (!Schema::hasTable('teachers')) {
            $this->error('The teachers table does not exist!');
            return 1;
        }
        
        // Check if name column exists
        if (Schema::hasColumn('teachers', 'name')) {
            $this->info('name column already exists in teachers table.');
        } else {
            // Try to identify what column is actually used for teacher names
            $columns = Schema::getColumnListing('teachers');
            $this->info('Current columns in teachers table: ' . implode(', ', $columns));
            
            // Check for typical name columns
            $nameColumns = ['full_name', 'fullname', 'first_name', 'lastname', 'last_name', 'nama'];
            $foundColumn = null;
            
            foreach ($nameColumns as $column) {
                if (Schema::hasColumn('teachers', $column)) {
                    $foundColumn = $column;
                    $this->info("Found possible name column: $column");
                    break;
                }
            }
            
            if ($foundColumn) {
                // Ask if we should create a view or add a real column
                $action = $this->choice(
                    "Do you want to create a real 'name' column or modify the controller to use '{$foundColumn}'?",
                    ['Add column', 'Modify controller'],
                    1
                );
                
                if ($action === 'Add column') {
                    $this->info("Adding 'name' column to teachers table...");
                    Schema::table('teachers', function ($table) {
                        $table->string('name')->nullable();
                    });
                    
                    // Copy data from the found column to the new name column
                    DB::statement("UPDATE teachers SET name = {$foundColumn}");
                    $this->info("Created 'name' column and copied data from '{$foundColumn}'");
                    
                    return 0;
                } else {
                    // We'll suggest modifying the controller
                    $this->info("Please modify your ScheduleController to use '{$foundColumn}' instead of 'name':");
                    $this->line("Change: Teacher::orderBy('name')->get();");
                    $this->line("To:    Teacher::orderBy('{$foundColumn}')->get();");
                    return 0;
                }
            } else {
                $this->warn('No typical name column found. Adding a new name column...');
                Schema::table('teachers', function ($table) {
                    $table->string('name')->nullable();
                });
                
                $this->info("Added 'name' column to teachers table. You'll need to populate it with data.");
                return 0;
            }
        }
        
        return 0;
    }
}
