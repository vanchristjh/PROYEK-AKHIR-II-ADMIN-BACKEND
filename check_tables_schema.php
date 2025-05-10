<?php

// Verify materials and classroom_material tables
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Materials table structure:\n";
$materialsColumns = DB::select('SHOW COLUMNS FROM materials');
foreach ($materialsColumns as $column) {
    echo "- {$column->Field} ({$column->Type})\n";
}

echo "\nClassroom_material table structure:\n";
$pivotColumns = DB::select('SHOW COLUMNS FROM classroom_material');
foreach ($pivotColumns as $column) {
    echo "- {$column->Field} ({$column->Type})\n";
}
