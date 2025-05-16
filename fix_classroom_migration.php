<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Check if classroom_id column already exists in the users table
$hasClassroomId = Schema::hasColumn('users', 'classroom_id');
echo "Does classroom_id exist in users table? " . ($hasClassroomId ? 'YES' : 'NO') . "\n";

// Check if role_id column exists in the users table
$hasRoleId = Schema::hasColumn('users', 'role_id');
echo "Does role_id exist in users table? " . ($hasRoleId ? 'YES' : 'NO') . "\n";

// List all migrations in the migrations table
echo "\nMigration status:\n";
$migrations = DB::table('migrations')->get();
foreach ($migrations as $migration) {
    echo "- {$migration->migration} (Batch: {$migration->batch})\n";
}

// Look for the problematic migration
$problemMigration = DB::table('migrations')
    ->where('migration', 'like', '%create_classrooms_table%')
    ->first();

if ($problemMigration) {
    echo "\nFound migration in the database: {$problemMigration->migration} (Batch: {$problemMigration->batch})\n";
} else {
    echo "\nNo classroom migration found in the database\n";
}

// Look for the failing migration file
$failingMigration = "2023_05_01_000001_create_classrooms_table.php";
$failingPath = __DIR__ . "/database/migrations/{$failingMigration}";

echo "\nChecking for the failing migration file at: {$failingPath}\n";
if (file_exists($failingPath)) {
    echo "The failing migration file EXISTS\n";
    
    // Option 1: Mark it as run in the database
    echo "\nOption 1: Mark it as run in the database\n";
    echo "To execute: php artisan migrate:add --path=database/migrations/{$failingMigration}\n";
    
    // Option 2: Rename the file so it won't be run
    echo "\nOption 2: Rename the file so it won't be run\n";
    echo "To execute: rename-item -path \"database/migrations/{$failingMigration}\" -newname \"_deprecated_{$failingMigration}\"\n";
} else {
    echo "The failing migration file DOES NOT EXIST\n";
}
