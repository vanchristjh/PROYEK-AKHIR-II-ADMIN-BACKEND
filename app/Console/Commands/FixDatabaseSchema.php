<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixDatabaseSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-database-schema';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix the database schema issues with role_user table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking database schema...');

        // Check if role_user table exists
        if (!Schema::hasTable('role_user')) {
            $this->info('Creating role_user table...');
            
            Schema::create('role_user', function ($table) {
                $table->id();
                $table->foreignId('role_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                
                $table->unique(['role_id', 'user_id']);
            });
            
            // Populate the table with existing user role data
            DB::statement('
                INSERT INTO role_user (role_id, user_id, created_at, updated_at)
                SELECT role_id, id, NOW(), NOW()
                FROM users
                WHERE role_id IS NOT NULL
            ');
            
            $this->info('role_user table created and populated!');
        } else {
            $this->info('role_user table already exists.');
        }

        $this->info('Database schema fixes complete!');
        
        return Command::SUCCESS;
    }
}
