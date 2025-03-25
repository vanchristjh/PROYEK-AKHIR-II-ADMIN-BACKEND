<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class SafeDbReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:safe-reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safely reset the database by disabling foreign key checks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Disabling foreign key checks...');
        Schema::disableForeignKeyConstraints();

        $this->info('Dropping all tables...');
        $tables = DB::select('SHOW TABLES');
        $dbName = DB::connection()->getDatabaseName();
        
        foreach ($tables as $table) {
            $tableName = array_values((array) $table)[0];
            $this->info("Dropping table: {$tableName}");
            DB::statement("DROP TABLE IF EXISTS {$tableName}");
        }

        $this->info('Re-enabling foreign key checks...');
        Schema::enableForeignKeyConstraints();

        $this->info('Running migrations...');
        Artisan::call('migrate', ['--force' => true]);
        
        $this->info('Database reset completed successfully!');
    }
}
