<?php

$migrationsDir = __DIR__ . '/database/migrations';

// Get all migration files
$files = glob($migrationsDir . '/*.php');
$tables = [];
$duplicates = [];

echo "Scanning migration files...\n";

foreach ($files as $file) {
    $filename = basename($file);
    
    // Extract table name from filename
    if (preg_match('/_create_([a-z_]+)_table\.php$/', $filename, $matches)) {
        $tableName = $matches[1];
        
        if (!isset($tables[$tableName])) {
            $tables[$tableName] = [];
        }
        
        $tables[$tableName][] = $filename;
    }
}

echo "\nPotential duplicate migrations:\n";
echo "============================\n";

foreach ($tables as $table => $migrations) {
    if (count($migrations) > 1) {
        echo "\nTable: {$table}\n";
        echo "Files:\n";
        
        usort($migrations, function($a, $b) {
            // Extract timestamps for sorting
            preg_match('/^(\d+)_/', $a, $matchA);
            preg_match('/^(\d+)_/', $b, $matchB);
            return $matchA[1] <=> $matchB[1];
        });
        
        foreach ($migrations as $index => $migration) {
            $keep = ($index === 0) ? "[KEEP - EARLIEST]" : "[CONSIDER REMOVING]";
            echo "  - {$migration} {$keep}\n";
            
            if ($index > 0) {
                $duplicates[] = $migration;
            }
        }
    }
}

echo "\n\nSuggested files to check or remove:\n";
echo "=================================\n";

foreach ($duplicates as $duplicate) {
    echo $duplicate . "\n";
}

echo "\nNOTE: Review each file before removing! Some might have different columns or might be needed.\n";
echo "You may want to rename files with .bak extension instead of deleting them.\n";
?>
