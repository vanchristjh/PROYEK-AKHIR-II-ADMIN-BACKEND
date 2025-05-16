<?php
// Simple script to check if teachers table exists

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $tables = DB::select('SHOW TABLES');
    echo "Database tables:\n";
    foreach ($tables as $table) {
        $tableName = array_values(get_object_vars($table))[0];
        echo "- $tableName\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
