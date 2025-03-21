<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class FixSqliteDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:fix-sqlite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix corrupted SQLite database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Only proceed if using SQLite
        if (config('database.default') !== 'sqlite') {
            $this->error('This command is only for SQLite databases.');
            return Command::FAILURE;
        }

        $databasePath = config('database.connections.sqlite.database');
        
        // Check if database file exists
        if (!File::exists($databasePath)) {
            $this->info("Database file doesn't exist. Creating new database file.");
            $this->createEmptyDatabase($databasePath);
            $this->runMigrations();
            return Command::SUCCESS;
        }
        
        // Backup the existing database before making changes
        $backupPath = $databasePath . '.' . time() . '.backup';
        $this->info("Backing up existing database to: $backupPath");
        try {
            File::copy($databasePath, $backupPath);
            $this->info("Backup created successfully.");
        } catch (\Exception $e) {
            $this->warn("Could not create backup: " . $e->getMessage());
            if (!$this->confirm('Continue without backup?', false)) {
                return Command::FAILURE;
            }
        }
        
        // Attempt to repair the database using SQLite CLI if available
        if ($this->attemptRepair($databasePath)) {
            $this->info("Database repair successful!");
            return Command::SUCCESS;
        }
        
        // If repair failed, offer to recreate the database
        if ($this->confirm('Repair failed. Do you want to recreate the database? THIS WILL DELETE ALL DATA.', true)) {
            $this->info("Recreating database...");
            
            // Remove the corrupted file
            File::delete($databasePath);
            
            // Create a new empty database
            $this->createEmptyDatabase($databasePath);
            
            // Run migrations
            $this->runMigrations();
            
            $this->info("Database recreated successfully.");
            return Command::SUCCESS;
        }
        
        $this->error("The database is still corrupted. No changes were made.");
        return Command::FAILURE;
    }
    
    private function createEmptyDatabase($path)
    {
        $directory = dirname($path);
        
        // Create the database directory if it doesn't exist
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        
        // Create an empty database file
        if (!File::exists($path)) {
            File::put($path, '');
            $this->info("Created empty database file at: $path");
            
            // Initialize the database with SQLite
            try {
                $pdo = new \PDO("sqlite:{$path}");
                $pdo->exec('PRAGMA journal_mode = WAL;');
                $pdo->exec('PRAGMA synchronous = NORMAL;');
                $pdo->exec('PRAGMA foreign_keys = ON;');
                $this->info("Initialized SQLite database with recommended settings.");
            } catch (\Exception $e) {
                $this->warn("Could not initialize database: " . $e->getMessage());
            }
        }
    }
    
    private function attemptRepair($databasePath)
    {
        $this->info("Attempting to repair database...");
        
        // Check if sqlite3 command is available
        exec('sqlite3 --version', $output, $returnVar);
        if ($returnVar !== 0) {
            $this->warn("SQLite3 command-line tool not found. Skipping repair attempt.");
            return false;
        }
        
        // Export database structure (if possible)
        $tempDir = storage_path('app/temp');
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }
        
        $schemaPath = $tempDir . '/schema.sql';
        $dataPath = $tempDir . '/data.sql';
        
        // Try to dump the schema (this may fail if database is severely corrupted)
        try {
            exec("sqlite3 \"$databasePath\" \".schema\" > \"$schemaPath\"", $output, $returnVar);
            if ($returnVar !== 0) {
                $this->warn("Could not extract schema from corrupted database.");
            } else {
                $this->info("Schema extracted successfully.");
            }
            
            // Try to dump data 
            exec("sqlite3 \"$databasePath\" \".dump\" > \"$dataPath\"", $output, $returnVar);
            if ($returnVar !== 0) {
                $this->warn("Could not extract data from corrupted database.");
            } else {
                $this->info("Data extracted successfully.");
            }
        } catch (\Exception $e) {
            $this->warn("Database repair via export/import failed: " . $e->getMessage());
            return false;
        }
        
        // Create a new database file
        $newDatabasePath = $databasePath . '.new';
        File::put($newDatabasePath, '');
        
        // Try to restore from schema and/or data
        $success = false;
        if (File::exists($schemaPath) && filesize($schemaPath) > 0) {
            exec("sqlite3 \"$newDatabasePath\" < \"$schemaPath\"", $output, $returnVar);
            if ($returnVar === 0) {
                $this->info("Schema restored to new database.");
                $success = true;
                
                // Now try to restore data if available
                if (File::exists($dataPath) && filesize($dataPath) > 0) {
                    exec("sqlite3 \"$newDatabasePath\" < \"$dataPath\"", $output, $returnVar);
                    if ($returnVar === 0) {
                        $this->info("Data restored to new database.");
                    } else {
                        $this->warn("Data restoration failed. You may need to re-enter your data.");
                    }
                }
            }
        }
        
        // If repair was successful, replace the old database with the new one
        if ($success) {
            File::delete($databasePath);
            File::move($newDatabasePath, $databasePath);
            $this->info("Database repaired and replaced successfully.");
            return true;
        } else {
            // Clean up
            if (File::exists($newDatabasePath)) {
                File::delete($newDatabasePath);
            }
            $this->warn("Database repair failed.");
            return false;
        }
    }
    
    private function runMigrations()
    {
        $this->info("Running migrations...");
        try {
            Artisan::call('migrate', ['--force' => true]);
            $this->info(Artisan::output());
            
            // Run seeders if needed
            if ($this->confirm('Do you want to run seeders to create initial data?', true)) {
                Artisan::call('db:seed', ['--force' => true]);
                $this->info(Artisan::output());
            }
            
            $this->info("Database migration completed successfully.");
            return true;
        } catch (\Exception $e) {
            $this->error("Migration failed: " . $e->getMessage());
            return false;
        }
    }
}
