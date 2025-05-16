<?php
/**
 * This script diagnoses the assignments table structure
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "Current database connection: " . config('database.default') . "\n";
echo "Database name: " . config('database.connections.' . config('database.default') . '.database') . "\n\n";

// Check if assignments table exists
if (!Schema::hasTable('assignments')) {
    echo "ERROR: assignments table does not exist!\n";
    exit(1);
}

// Get all columns in assignments table
$columns = Schema::getColumnListing('assignments');
echo "Columns in assignments table:\n";
echo implode(", ", $columns) . "\n\n";

// Check for teacher_id specifically
$hasTeacherId = in_array('teacher_id', $columns);
echo "teacher_id column exists: " . ($hasTeacherId ? 'YES' : 'NO') . "\n";

// If teacher_id exists, check its properties
if ($hasTeacherId) {
    $columnInfo = DB::select("SHOW COLUMNS FROM assignments WHERE Field = 'teacher_id'")[0];
    echo "teacher_id column type: " . $columnInfo->Type . "\n";
    echo "teacher_id allows NULL: " . ($columnInfo->Null === 'YES' ? 'YES' : 'NO') . "\n";
    
    // Check foreign keys
    $foreignKeys = DB::select("
        SELECT
            CONSTRAINT_NAME as constraint_name,
            COLUMN_NAME as column_name,
            REFERENCED_TABLE_NAME as referenced_table,
            REFERENCED_COLUMN_NAME as referenced_column
        FROM
            information_schema.KEY_COLUMN_USAGE
        WHERE
            TABLE_SCHEMA = DATABASE() AND
            TABLE_NAME = 'assignments' AND
            COLUMN_NAME = 'teacher_id' AND
            REFERENCED_TABLE_NAME IS NOT NULL
    ");
    
    if (count($foreignKeys) > 0) {
        echo "Foreign key for teacher_id: {$foreignKeys[0]->constraint_name}\n";
        echo "References: {$foreignKeys[0]->referenced_table}({$foreignKeys[0]->referenced_column})\n";
    } else {
        echo "No foreign key constraint found for teacher_id\n";
    }
    
    // Count assignments with teacher_id
    $totalCount = DB::table('assignments')->count();
    $withTeacherId = DB::table('assignments')->whereNotNull('teacher_id')->count();
    echo "\nTotal assignments: $totalCount\n";
    echo "Assignments with teacher_id: $withTeacherId\n";
    
    // Sample data
    $samples = DB::table('assignments')
        ->select('id', 'title', 'teacher_id', 'created_by')
        ->limit(5)
        ->get();
    
    echo "\nSample assignments:\n";
    foreach ($samples as $sample) {
        echo "ID: {$sample->id}, Title: {$sample->title}, Teacher ID: {$sample->teacher_id}, Created By: {$sample->created_by}\n";
    }
}

echo "\nDiagnostic complete!\n";
