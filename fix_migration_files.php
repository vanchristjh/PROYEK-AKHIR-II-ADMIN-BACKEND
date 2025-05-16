<?php

// This script renames migration files without proper timestamps and removes deprecated files

// Include the autoloader
require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$migrationsDir = __DIR__ . '/database/migrations/';

// Files to remove (empty or problematic files)
$filesToRemove = [
    '_deprecated_2023_05_01_000001_create_classrooms_table.php',
    '[timestamp]_create_materials_table.php'
];

// Files to rename with proper timestamps
$filesToRename = [
    'add_teacher_id_to_materials_table.php' => '2024_07_02_000001_add_teacher_id_to_materials_table.php',
    'create_users_table.php' => '2024_07_02_000002_create_users_table.php',
    'create_subject_teacher_table.php' => '2024_07_02_000003_create_subject_teacher_table.php'
];

// Remove problematic files
foreach ($filesToRemove as $file) {
    $fullPath = $migrationsDir . $file;
    if (file_exists($fullPath)) {
        if (unlink($fullPath)) {
            echo "Successfully deleted file: {$file}\n";
        } else {
            echo "Failed to delete file: {$file}\n";
        }
    } else {
        echo "File not found: {$file}\n";
    }
}

// Rename files with proper timestamps
foreach ($filesToRename as $oldName => $newName) {
    $oldPath = $migrationsDir . $oldName;
    $newPath = $migrationsDir . $newName;
    
    if (file_exists($oldPath)) {
        if (rename($oldPath, $newPath)) {
            echo "Successfully renamed: {$oldName} -> {$newName}\n";
        } else {
            echo "Failed to rename: {$oldName}\n";
        }
    } else {
        echo "File not found: {$oldName}\n";
    }
}

echo "Migration files cleanup completed.\n";
