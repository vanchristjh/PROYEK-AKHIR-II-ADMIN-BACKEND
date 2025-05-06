<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckDatabaseTables extends Command
{
    protected $signature = 'db:check-tables';
    protected $description = 'Check which tables exist in the database';

    public function handle()
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = DB::connection()->getDatabaseName();
        $propertyName = 'Tables_in_' . $dbName;

        $this->info("Tables in database '{$dbName}':");
        
        foreach ($tables as $table) {
            $tableName = $table->$propertyName;
            $this->info("- {$tableName}");
            
            // Optionally show columns in each table
            $columns = Schema::getColumnListing($tableName);
            foreach ($columns as $column) {
                $this->info("  └── {$column}");
            }
        }

        return Command::SUCCESS;
    }
}
