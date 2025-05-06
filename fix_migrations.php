<?php

// This script removes problematic migration entries from the migrations table

// Include the autoloader
require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the migration to delete
$migrationToDelete = '2025_05_06_164147_create_personal_access_tokens_table';

// Use the DB facade to delete the record
try {
    $deleted = DB::table('migrations')
        ->where('migration', $migrationToDelete)
        ->delete();
    
    if ($deleted) {
        echo "Successfully deleted migration record: {$migrationToDelete}\n";
    } else {
        echo "No migration record found with name: {$migrationToDelete}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Also check for any duplicate personal_access_tokens migrations
try {
    $duplicates = DB::table('migrations')
        ->where('migration', 'like', '%create_personal_access_tokens_table%')
        ->get();
    
    echo "Found " . count($duplicates) . " personal_access_tokens migration records:\n";
    foreach ($duplicates as $duplicate) {
        echo "- " . $duplicate->migration . " (Batch: " . $duplicate->batch . ")\n";
    }
} catch (Exception $e) {
    echo "Error checking for duplicates: " . $e->getMessage() . "\n";
}