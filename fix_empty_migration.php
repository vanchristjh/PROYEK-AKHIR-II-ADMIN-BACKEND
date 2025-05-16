<?php

// This script removes the empty migration file that's causing problems

// Include the autoloader
require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Path to the problematic migration file
$emptyMigrationFile = __DIR__ . '/database/migrations/2023_06_30_add_username_to_users_table.php';

// Check if the file exists
if (file_exists($emptyMigrationFile)) {
    // Check if the file is empty
    if (filesize($emptyMigrationFile) === 0) {
        // Remove the empty file
        if (unlink($emptyMigrationFile)) {
            echo "Successfully deleted empty migration file: 2023_06_30_add_username_to_users_table.php\n";
        } else {
            echo "Failed to delete the empty migration file.\n";
        }
    } else {
        echo "Migration file exists but is not empty. Please check its content manually.\n";
    }
} else {
    echo "Migration file not found.\n";
}

// Also ensure the migration is not in the migrations table
try {
    $migrationName = '2023_06_30_add_username_to_users_table';
    $deleted = DB::table('migrations')
        ->where('migration', $migrationName)
        ->delete();
    
    if ($deleted) {
        echo "Successfully deleted migration record from the migrations table: {$migrationName}\n";
    } else {
        echo "No migration record found in the migrations table with name: {$migrationName}\n";
    }
} catch (Exception $e) {
    echo "Error checking migrations table: " . $e->getMessage() . "\n";
}

echo "Done! You can now try running 'php artisan migrate:fresh --seed' again.\n";
