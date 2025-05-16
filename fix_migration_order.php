<?php

// This script fixes the migration issue by ensuring materials table migrations have correct timestamps

// Include the autoloader
require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Define the paths to the problematic migration files
$materialsFile1 = __DIR__ . '/database/migrations/2025_05_16_000001_create_materials_table.php';
$materialsFile2 = __DIR__ . '/database/migrations/2025_05_16_100000_create_materials_table.php';
$classroomMaterialFile = __DIR__ . '/database/migrations/2024_07_05_000010_create_classroom_material_table.php';

// Define new timestamps (ensure materials migrations run before classroom_material)
$newMaterialsTimestamp1 = '2024_07_01_000001';
$newMaterialsTimestamp2 = '2024_07_01_100000';

// Function to rename a migration file with a new timestamp
function renameMigrationFile($oldPath, $newTimestamp) {
    if (!file_exists($oldPath)) {
        echo "File not found: $oldPath\n";
        return false;
    }
    
    $pathInfo = pathinfo($oldPath);
    $oldFilename = $pathInfo['filename'];
    $oldTimestamp = substr($oldFilename, 0, 17); // Extract timestamp part
    $filenameSuffix = substr($oldFilename, 18); // Extract the rest of the filename
    
    $newFilename = $newTimestamp . '_' . $filenameSuffix;
    $newPath = $pathInfo['dirname'] . '/' . $newFilename . '.' . $pathInfo['extension'];
    
    if (rename($oldPath, $newPath)) {
        echo "Successfully renamed migration: $oldFilename -> $newFilename\n";
        return true;
    } else {
        echo "Failed to rename migration: $oldPath\n";
        return false;
    }
}

// Rename the materials migration files
renameMigrationFile($materialsFile1, $newMaterialsTimestamp1);
renameMigrationFile($materialsFile2, $newMaterialsTimestamp2);

echo "Migration timestamps updated. You can now try running 'php artisan migrate:fresh --seed' again.\n";
