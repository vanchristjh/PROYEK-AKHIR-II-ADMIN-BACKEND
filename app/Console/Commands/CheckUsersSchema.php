<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckUsersSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-users-schema';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the schema of the users table to confirm column names';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking users table schema...');
        
        // Get column information from the users table
        $columns = Schema::getColumnListing('users');
        
        $this->info('Available columns in users table:');
        foreach ($columns as $column) {
            $this->line("- $column");
        }
        
        // Specifically check for phone-related columns
        $phoneColumns = array_filter($columns, function($column) {
            return str_contains($column, 'phone');
        });
        
        if (!empty($phoneColumns)) {
            $this->info('Phone-related columns:');
            foreach ($phoneColumns as $column) {
                $this->line("- $column");
            }
        } else {
            $this->warn('No phone-related columns found!');
        }
        
        return Command::SUCCESS;
    }
}
