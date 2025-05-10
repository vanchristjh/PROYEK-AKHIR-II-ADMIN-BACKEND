<?php

// Verify material creation and relationship
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Check materials table
$materials = DB::table('materials')->get();
echo "Materials in database:\n";
foreach ($materials as $material) {
    echo "ID: {$material->id}, Title: {$material->title}\n";
}

// Check classroom_material pivot table
$relationships = DB::table('classroom_material')->get();
echo "\nClassroom-Material relationships:\n";
foreach ($relationships as $rel) {
    echo "Material ID: {$rel->material_id}, Classroom ID: {$rel->classroom_id}\n";
}
