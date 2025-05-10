<?php

/**
 * This script fixes the foreign key constraint issue between submissions and assignments tables
 */

// Include the autoloader
require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Starting database schema fix for submissions table...\n";

// Step 1: Check if submissions table exists and drop it if needed
if (Schema::hasTable('submissions')) {
    echo "Dropping existing submissions table...\n";
    Schema::dropIfExists('submissions');
    echo "Submissions table dropped.\n";
} else {
    echo "Submissions table does not exist yet.\n";
}

// Step 2: Check if assignments table exists
if (!Schema::hasTable('assignments')) {
    echo "Error: Assignments table does not exist. Please run migrations for the assignments table first.\n";
    exit(1);
}

echo "Assignments table exists.\n";

// Step 3: Create the submissions table with proper foreign key constraint
echo "Creating submissions table with proper foreign key constraint...\n";
Schema::create('submissions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
    $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
    $table->string('file_path')->nullable();
    $table->text('notes')->nullable();
    $table->decimal('score', 5, 2)->nullable();
    $table->text('feedback')->nullable();
    $table->timestamp('submitted_at')->nullable();
    $table->timestamp('graded_at')->nullable();
    $table->timestamps();
});

echo "Submissions table created successfully.\n";
echo "Done fixing database schema.\n";
