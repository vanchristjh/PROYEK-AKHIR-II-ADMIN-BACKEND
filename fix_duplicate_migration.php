<?php

// Load the Laravel app without running through the HTTP kernel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// The problematic migration
$migrationToFix = '2025_05_06_164147_create_personal_access_tokens_table';

// Get the latest batch number
$latestBatch = DB::table('migrations')->max('batch');

try {
    // Create a new entry in the migrations table
    DB::table('migrations')->insert([
        'migration' => $migrationToFix,
        'batch' => $latestBatch + 1
    ]);
    
    echo "Success! Added entry for \"{$migrationToFix}\" to the migrations table.\n";
    echo "You should now be able to run migrations without the duplicate table error.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}