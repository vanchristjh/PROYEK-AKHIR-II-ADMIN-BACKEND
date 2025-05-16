<?php

// This script will handle the migration and provide a detailed error message if something fails

try {
    echo "Starting migration process...\n";
    
    // Execute the migrate:fresh command
    $output = shell_exec('php artisan migrate:fresh');
    echo $output;
    
    echo "Migration completed successfully!\n";
    
    // Execute the db:seed command
    echo "Starting database seeding...\n";
    $output = shell_exec('php artisan db:seed');
    echo $output;
    
    echo "Database seeding completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error during migration: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
