<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = [];
$migrations = [];

// Get all migration files
$files = glob('database/migrations/*.php');
foreach ($files as $file) {
    $basename = basename($file);
    if (strpos($basename, '_deprecated_') === 0) {
        continue; // Skip deprecated files
    }

    $content = file_get_contents($file);
    
    // Extract table names being created
    if (preg_match_all('/Schema::create\([\'"]([^\'"]+)[\'"]/', $content, $matches)) {
        foreach ($matches[1] as $table) {
            if (!isset($tables[$table])) {
                $tables[$table] = [];
            }
            $tables[$table][] = $basename;
        }
    }
    
    // Extract table names being modified
    if (preg_match_all('/Schema::table\([\'"]([^\'"]+)[\'"]/', $content, $matches)) {
        foreach ($matches[1] as $table) {
            if (!isset($migrations[$table])) {
                $migrations[$table] = [];
            }
            $migrations[$table][] = $basename;
        }
    }
}

// Display tables with multiple creation migrations
echo "Tables with multiple creation migrations:\n";
foreach ($tables as $table => $files) {
    if (count($files) > 1) {
        echo "Table '$table' is created in " . count($files) . " migrations:\n";
        foreach ($files as $file) {
            echo "  - $file\n";
        }
        echo "\n";
    }
}

// Display tables with multiple modifications
echo "\nTables with multiple modifications:\n";
foreach ($migrations as $table => $files) {
    if (count($files) > 1) {
        echo "Table '$table' is modified in " . count($files) . " migrations:\n";
        foreach ($files as $file) {
            echo "  - $file\n";
        }
        echo "\n";
    }
}

// Check for column conflicts
echo "\nChecking for column conflicts...\n";
$db = app('db');
$schema = $db->getSchemaBuilder();
$existingTables = $db->connection()->getDoctrineSchemaManager()->listTableNames();

foreach ($existingTables as $table) {
    if ($table == 'migrations') continue;
    
    $columns = $schema->getColumnListing($table);
    echo "Table '$table' columns: " . implode(', ', $columns) . "\n";
}
