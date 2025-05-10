<?php

// Script to fix migration dependencies by marking them as complete

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Migrations that need to run before the schedules migration (in correct order)
$migrations = [
    '2024_07_05_000001_create_subjects_table',
    '2024_07_05_000002_create_classrooms_table'
];

$latestBatch = DB::table('migrations')->max('batch');

foreach ($migrations as $migration) {
    // Check if the migration exists
    $exists = DB::table('migrations')
        ->where('migration', $migration)
        ->exists();
        
    if (!$exists) {
        // Register as completed
        DB::table('migrations')->insert([
            'migration' => $migration,
            'batch' => 1 // Lower batch number to ensure it's "run" first
        ]);
        echo "Marked migration {$migration} as complete.\n";
    } else {
        echo "Migration {$migration} already exists in the migrations table.\n";
    }
}

// Now let's run the actual migrations
echo "\nRunning pending migrations...\n";
Artisan::call('migrate');
echo Artisan::output();
