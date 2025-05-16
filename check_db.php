<?php
// This PHP script is used to check the database schema and content

// 1. First require the autoloader
require __DIR__ . '/vendor/autoload.php';

// 2. Bootstrap the Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "===== CHECKING DATABASE FOR FOREIGN KEY ISSUE =====\n\n";

// 3. Check if teacher with ID 2 exists
echo "Checking teacher with ID 2:\n";
$teacher = DB::table('teachers')->where('id', 2)->first();
var_dump($teacher);

// 4. Check if subject with ID 1 exists
echo "\nChecking subject with ID 1:\n";
$subject = DB::table('subjects')->where('id', 1)->first();
var_dump($subject);

// 5. Check users table to see if there's correlation
echo "\nChecking users that might be related to teachers:\n";
$users = DB::table('users')->get();
print_r($users);

// 6. Optional: Check all teachers
echo "\nAll teachers in the database:\n";
$allTeachers = DB::table('teachers')->get();
print_r($allTeachers);

// 7. Optional: Examine the subject_teacher table
echo "\nExisting entries in subject_teacher table:\n";
$subjectTeachers = DB::table('subject_teacher')->get();
print_r($subjectTeachers);

echo "\n===== END OF DATABASE CHECK =====\n";
