<?php

require_once __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

$app = app();
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check if the materials table exists
if (Schema::hasTable('materials')) {
    echo "Materials table exists!\n";
    
    // Get the columns in the materials table
    $columns = Schema::getColumnListing('materials');
    echo "Columns in materials table: " . implode(', ', $columns) . "\n";
} else {
    echo "Materials table does not exist!\n";
}

// List all tables
$tables = DB::select('SHOW TABLES');
echo "All tables in the database:\n";
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    echo "- $tableName\n";
}
