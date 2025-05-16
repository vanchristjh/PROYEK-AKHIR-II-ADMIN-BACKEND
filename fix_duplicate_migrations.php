<?php
/**
 * This script will help identify and remove duplicate migration files
 */

// List of duplicate migration files to be removed
$duplicateFiles = [
    // Duplicate subjects tables
    __DIR__ . '/database/migrations/2023_01_01_000001_create_subjects_table.php',
    __DIR__ . '/database/migrations/2024_06_10_000000_create_subjects_table.php',
    __DIR__ . '/database/migrations/2024_06_10_000001_create_subjects_table.php',
    __DIR__ . '/database/migrations/2024_07_05_000001_create_subjects_table.php',
    
    // Duplicate classrooms tables
    __DIR__ . '/database/migrations/2023_04_01_000000_create_classrooms_table.php',
    __DIR__ . '/database/migrations/2024_06_10_000000_create_classrooms_table.php',
    __DIR__ . '/database/migrations/2024_06_10_000001_create_classrooms_table.php',
    __DIR__ . '/database/migrations/2024_07_05_000002_create_classrooms_table.php',
    
    // Duplicate classroom_subject tables
    __DIR__ . '/database/migrations/2023_04_15_000000_create_classroom_subject_table.php',
    __DIR__ . '/database/migrations/2023_05_01_000002_create_classroom_subject_table.php',
    __DIR__ . '/database/migrations/2024_07_05_000003_create_classroom_subject_table.php',
    
    // Duplicate subject_teacher tables
    __DIR__ . '/database/migrations/2023_05_01_000003_create_subject_teacher_table.php',
    __DIR__ . '/database/migrations/2023_07_30_create_subject_teacher_table.php',
    __DIR__ . '/database/migrations/2024_06_10_000004_create_subject_teacher_table.php',
    __DIR__ . '/database/migrations/2024_07_05_000004_create_subject_teacher_table.php',
    
    // Duplicate schedules tables
    __DIR__ . '/database/migrations/2024_07_05_000005_create_schedules_table.php',
    
    // Duplicate assignments tables (this is causing your error)
    __DIR__ . '/database/migrations/2023_08_15_010000_create_assignments_table.php',
    __DIR__ . '/database/migrations/2024_06_10_000000_create_assignments_table.php',
    __DIR__ . '/database/migrations/2024_06_10_000002_create_assignments_table.php',
    __DIR__ . '/database/migrations/2024_07_05_000006_create_assignments_table.php',
    
    // Duplicate submissions tables
    __DIR__ . '/database/migrations/2023_08_10_000002_create_submissions_table.php',
    __DIR__ . '/database/migrations/2024_07_05_000007_create_submissions_table.php',
    
    // Duplicate announcements tables
    __DIR__ . '/database/migrations/2023_08_01_000000_create_announcements_table.php',
    
    // Duplicate assignment_submissions tables
    __DIR__ . '/database/migrations/2023_08_22_000001_create_assignment_submissions_table.php',
    __DIR__ . '/database/migrations/2023_08_25_000000_create_assignment_submissions_table.php',
    
    // Duplicate subject_user tables 
    __DIR__ . '/database/migrations/2024_05_14_create_subject_user_table.php',
    
    // Duplicate grades tables
    __DIR__ . '/database/migrations/2024_06_10_000003_create_grades_table.php',
    
    // Duplicate file column migrations
    __DIR__ . '/database/migrations/2023_05_10_000001_add_file_column_to_submissions_table.php'
];

// Keep track of removed files
$removedFiles = [];
$notFoundFiles = [];

// Process each duplicate file
foreach ($duplicateFiles as $file) {
    if (file_exists($file)) {
        // Creating a backup before deletion
        $backupPath = $file . '.bak';
        copy($file, $backupPath);
        
        // Remove the file
        if (unlink($file)) {
            $removedFiles[] = $file;
            echo "Removed: " . basename($file) . " (backup created at " . basename($backupPath) . ")\n";
        } else {
            echo "Failed to remove: " . basename($file) . "\n";
        }
    } else {
        $notFoundFiles[] = $file;
        echo "File not found: " . basename($file) . "\n";
    }
}

// Summary
echo "\n--- Summary ---\n";
echo "Total files processed: " . count($duplicateFiles) . "\n";
echo "Files removed: " . count($removedFiles) . "\n";
echo "Files not found: " . count($notFoundFiles) . "\n";

echo "\nTo run a new migration, use the following command:\n";
echo "php artisan migrate:fresh --seed\n";
