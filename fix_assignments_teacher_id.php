<?php
/**
 * This script adds the teacher_id column to the assignments table if it's missing
 * and sets its value to the same as created_by
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

// Check if teacher_id column exists
if (!Schema::hasColumn('assignments', 'teacher_id')) {
    echo "Adding teacher_id column to assignments table...\n";
    
    Schema::table('assignments', function (Blueprint $table) {
        $table->unsignedBigInteger('teacher_id')->nullable()->after('classroom_id');
    });
    
    echo "Column added successfully.\n";
    
    // Update teacher_id with created_by
    $updated = DB::update('UPDATE assignments SET teacher_id = created_by WHERE teacher_id IS NULL');
    echo "Updated $updated assignments with teacher_id.\n";
    
    // Add foreign key constraint
    Schema::table('assignments', function (Blueprint $table) {
        $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
    });
    
    echo "Added foreign key constraint.\n";
} else {
    echo "teacher_id column already exists in assignments table.\n";
    
    // Check if any assignments have NULL teacher_id
    $nullCount = DB::table('assignments')->whereNull('teacher_id')->count();
    
    if ($nullCount > 0) {
        echo "Found $nullCount assignments with NULL teacher_id. Updating...\n";
        $updated = DB::update('UPDATE assignments SET teacher_id = created_by WHERE teacher_id IS NULL');
        echo "Updated $updated assignments.\n";
    } else {
        echo "All assignments already have teacher_id set.\n";
    }
}

echo "Done!\n";
