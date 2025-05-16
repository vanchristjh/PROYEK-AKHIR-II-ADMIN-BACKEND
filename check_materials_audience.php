<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

if (Schema::hasColumn('materials', 'audience')) {
    echo "✓ The 'audience' column exists in the materials table.\n";

    // Get the column details
    $columns = DB::select("SHOW COLUMNS FROM materials WHERE Field = 'audience'");
    if (!empty($columns)) {
        $column = $columns[0];
        echo "Column details:\n";
        echo "Type: " . $column->Type . "\n";
        echo "Null: " . $column->Null . "\n"; 
        echo "Default: " . $column->Default . "\n";
    }
} else {
    echo "✗ The 'audience' column DOES NOT exist in the materials table.\n";
}

// List all columns in materials table
$columns = Schema::getColumnListing('materials');
echo "\nAll columns in the materials table:\n";
print_r($columns);
