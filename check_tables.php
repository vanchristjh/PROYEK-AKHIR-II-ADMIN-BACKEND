<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';

    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    // Check if tables exist
    echo "Users table exists: " . (Schema::hasTable('users') ? 'YES' : 'NO') . "\n";
    echo "Classrooms table exists: " . (Schema::hasTable('classrooms') ? 'YES' : 'NO') . "\n";

    if (Schema::hasTable('users')) {
        echo "Users table columns: " . implode(', ', Schema::getColumnListing('users')) . "\n";
    }

    if (Schema::hasTable('classrooms')) {
        echo "Classrooms table columns: " . implode(', ', Schema::getColumnListing('classrooms')) . "\n";
    }

    // Check if the relationship exists
    $hasRelationship = Schema::hasColumn('users', 'classroom_id') && Schema::hasTable('classrooms');
    echo "User-Classroom relationship exists: " . ($hasRelationship ? 'YES' : 'NO') . "\n";

    // Check migrations table
    echo "\nMigration status for classroom-related migrations:\n";
    $migrations = DB::table('migrations')
        ->where('migration', 'like', '%classroom%')
        ->get();

    foreach ($migrations as $migration) {
        echo "- {$migration->migration} (Batch: {$migration->batch})\n";
    }

    echo "\nTotal number of migrations: " . DB::table('migrations')->count() . "\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n";
}
