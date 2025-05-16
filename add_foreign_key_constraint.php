<?php
/**
 * This script adds a foreign key constraint for teacher_id in assignments table
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

// Check if foreign key exists
$foreignKeys = DB::select("
    SELECT
        CONSTRAINT_NAME as constraint_name
    FROM
        information_schema.KEY_COLUMN_USAGE
    WHERE
        TABLE_SCHEMA = DATABASE() AND
        TABLE_NAME = 'assignments' AND
        COLUMN_NAME = 'teacher_id' AND
        REFERENCED_TABLE_NAME IS NOT NULL
");

if (count($foreignKeys) > 0) {
    echo "Foreign key constraint already exists: " . $foreignKeys[0]->constraint_name . "\n";
} else {
    echo "Adding foreign key constraint...\n";
    
    try {
        Schema::table('assignments', function (Blueprint $table) {
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        echo "Foreign key constraint added successfully!\n";
    } catch (\Exception $e) {
        echo "Error adding foreign key constraint: " . $e->getMessage() . "\n";
    }
}

echo "\nOperation complete!\n";
