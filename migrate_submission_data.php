<?php
// Script to migrate data from submissions table to assignment_submissions table

// Include the Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Set up the Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the database connection
$db = $app->make('db');

echo "Starting migration of submissions data...\n";

// Check if both tables exist
if (!$db->schema()->hasTable('submissions')) {
    echo "ERROR: The submissions table does not exist!\n";
    exit(1);
}

if (!$db->schema()->hasTable('assignment_submissions')) {
    echo "ERROR: The assignment_submissions table does not exist!\n";
    exit(1);
}

// Get all submissions
$submissions = $db->table('submissions')->get();
echo "Found " . $submissions->count() . " submissions to migrate.\n";

$migratedCount = 0;
$skippedCount = 0;
$errorCount = 0;

foreach ($submissions as $submission) {
    // Check if a submission with this assignment_id and student_id already exists in assignment_submissions
    $exists = $db->table('assignment_submissions')
        ->where('assignment_id', $submission->assignment_id)
        ->where('student_id', $submission->student_id)
        ->exists();

    if ($exists) {
        echo "Skipping duplicate submission for assignment {$submission->assignment_id} and student {$submission->student_id}\n";
        $skippedCount++;
        continue;
    }

    // Insert into assignment_submissions table
    try {
        $db->table('assignment_submissions')->insert([
            'assignment_id' => $submission->assignment_id,
            'student_id' => $submission->student_id,
            'file_path' => $submission->file_path ?? null,
            'notes' => $submission->notes ?? null,
            'submission_date' => $submission->submitted_at ?? now(),
            'grade' => $submission->score ?? null,
            'graded_at' => $submission->graded_at ?? null,
            'feedback' => $submission->feedback ?? null,
            'created_at' => $submission->created_at ?? now(),
            'updated_at' => $submission->updated_at ?? now(),
        ]);

        $migratedCount++;
        echo "Migrated submission {$submission->id}\n";
    } catch (Exception $e) {
        echo "ERROR migrating submission {$submission->id}: " . $e->getMessage() . "\n";
        $errorCount++;
    }
}

echo "\nMigration completed:\n";
echo "{$migratedCount} submissions migrated successfully\n";
echo "{$skippedCount} submissions skipped (duplicates)\n";
echo "{$errorCount} submissions had errors\n";

echo "\nMigration process completed.\n";
