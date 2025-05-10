<?php

// Script to fix the database issue by marking the existing migration as completed
// This is needed when migrations run out of order or have dependencies

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// The problematic migration
$migration = '2024_07_05_000005_create_schedules_table';

try {
    // Get the latest batch number
    $latestBatch = DB::table('migrations')->max('batch');
    
    // Check if the migration already exists in the table
    $exists = DB::table('migrations')
        ->where('migration', $migration)
        ->exists();
        
    if (!$exists) {
        // Insert the migration record with a low batch number
        // to make it run first (before the add_day_column migration)
        DB::table('migrations')->insert([
            'migration' => $migration,
            'batch' => 1 // Using batch 1 to ensure it runs first
        ]);
        
        echo "Success! Added entry for \"{$migration}\" to the migrations table.\n";
    } else {
        echo "Migration \"{$migration}\" already exists in the migrations table.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
