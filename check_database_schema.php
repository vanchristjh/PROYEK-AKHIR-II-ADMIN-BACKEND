<?php

// Check if the required tables for schedules exist

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = ['subjects', 'users', 'classrooms'];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo "Table {$table} exists.\n";
        
        // Get column information
        $columns = DB::select("SHOW COLUMNS FROM {$table}");
        echo "Columns in {$table}:\n";
        foreach ($columns as $column) {
            echo " - {$column->Field} ({$column->Type})\n";
        }
        echo "\n";
    } else {
        echo "Table {$table} does NOT exist!\n";
    }
}

// Check which migrations are in the database
echo "\nMigrations in the database:\n";
$migrations = DB::table('migrations')->orderBy('migration')->get();
foreach ($migrations as $migration) {
    echo " - {$migration->migration} (batch {$migration->batch})\n";
}
