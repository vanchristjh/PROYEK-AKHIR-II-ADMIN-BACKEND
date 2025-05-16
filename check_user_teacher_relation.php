<?php
// Check the relationship between users and teachers tables

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;

// Check teachers table structure
$teachersColumns = DB::select('SHOW COLUMNS FROM teachers');
echo "Teachers table columns:\n";
foreach ($teachersColumns as $column) {
    echo "- {$column->Field} ({$column->Type})\n";
}

// Count records in teachers table
$teacherCount = DB::table('teachers')->count();
echo "\nNumber of records in teachers table: " . $teacherCount . "\n";

// Sample records from teachers table
$teacherRecords = DB::table('teachers')->limit(5)->get();
echo "\nSample records from teachers table:\n";
foreach ($teacherRecords as $teacher) {
    echo "ID: {$teacher->id}, ";
    // Get other columns dynamically
    foreach ((array)$teacher as $key => $value) {
        if ($key != 'id') {
            echo "$key: " . (is_null($value) ? "NULL" : $value) . ", ";
        }
    }
    echo "\n";
}

// Check if there's a foreign key to users
$userTeacherRelation = DB::table('teachers')
    ->join('users', 'teachers.user_id', '=', 'users.id')
    ->select('teachers.*', 'users.name', 'users.email', 'users.role_id')
    ->limit(5)
    ->get();

echo "\nTeachers with associated users:\n";
if (count($userTeacherRelation) > 0) {
    foreach ($userTeacherRelation as $relation) {
        echo "Teacher ID: {$relation->id}, User Name: {$relation->name}, User Email: {$relation->email}, Role ID: {$relation->role_id}\n";
    }
} else {
    echo "No relationships found or join failed. Checking for user_id column in teachers table...\n";
    
    // Check if teachers table has user_id column
    $hasUserIdColumn = false;
    foreach ($teachersColumns as $column) {
        if ($column->Field === 'user_id') {
            $hasUserIdColumn = true;
            break;
        }
    }
    
    echo "Teachers table has user_id column: " . ($hasUserIdColumn ? "Yes" : "No") . "\n";
}
