<?php

// This script helps identify and potentially clean up duplicate migrations

// Path to migrations directory
$migrationsPath = __DIR__ . '/database/migrations';

// Get all migration files
$files = scandir($migrationsPath);

// Group files by the table they create
$tableGroups = [];
foreach ($files as $file) {
    if ($file === '.' || $file === '..') {
        continue;
    }
    
    // Extract table name from filename (assuming standard naming convention)
    if (preg_match('/_create_([a-z_]+)_table\.php$/', $file, $matches)) {
        $tableName = $matches[1];
        if (!isset($tableGroups[$tableName])) {
            $tableGroups[$tableName] = [];
        }
        $tableGroups[$tableName][] = $file;
    }
}

// Output results
echo "=== Migration Duplicates Report ===\n\n";
$hasDuplicates = false;

foreach ($tableGroups as $table => $files) {
    if (count($files) > 1) {
        $hasDuplicates = true;
        echo "Table '$table' has " . count($files) . " migration files:\n";
        foreach ($files as $index => $file) {
            echo "  " . ($index + 1) . ". $file\n";
        }
        echo "\n";
    }
}

if (!$hasDuplicates) {
    echo "No duplicate migrations found.\n";
}

echo "\nTo fix duplicates, consider:\n";
echo "1. Keeping the earliest migration and modifying it if needed\n";
echo "2. Making later migrations no-ops (empty up/down methods)\n";
echo "3. Using Schema::hasTable() checks in all migrations\n";
echo "4. Consider renaming migration classes to avoid conflicts\n";
