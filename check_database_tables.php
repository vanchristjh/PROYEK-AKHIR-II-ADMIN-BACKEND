<?php
// Check database tables

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Check if teachers table exists
$teachersTableExists = Schema::hasTable('teachers');
echo "Teachers table exists: " . ($teachersTableExists ? "Yes" : "No") . PHP_EOL;

// Get all tables
$tables = DB::select('SHOW TABLES');
echo "All database tables:" . PHP_EOL;
foreach ($tables as $table) {
    $tableName = array_values(get_object_vars($table))[0];
    echo "- " . $tableName . PHP_EOL;
}

// If teachers table exists, check its structure
if ($teachersTableExists) {
    $columns = Schema::getColumnListing('teachers');
    echo "Teachers table columns:" . PHP_EOL;
    foreach ($columns as $column) {
        echo "- " . $column . PHP_EOL;
    }
    
    // Count records
    $teacherCount = DB::table('teachers')->count();
    echo "Number of records in teachers table: " . $teacherCount . PHP_EOL;
}

echo "Done." . PHP_EOL;
