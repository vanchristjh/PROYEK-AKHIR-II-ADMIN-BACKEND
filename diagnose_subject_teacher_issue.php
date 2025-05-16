<?php
// Script to diagnose the subject_teacher foreign key constraint issue

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "===== DIAGNOSING SUBJECT_TEACHER FOREIGN KEY ISSUE =====\n\n";

// Check if subject with ID 3 exists
echo "Checking subject with ID 3:\n";
$subject = DB::table('subjects')->where('id', 3)->first();
var_dump($subject);

// Check if teacher with ID 2 exists
echo "\nChecking teacher with ID 2:\n";
$teacher = DB::table('teachers')->where('id', 2)->first();
var_dump($teacher);

// Check users table to find potential teacher data
echo "\nChecking users with teacher role (role_id = 2):\n";
$teacherUsers = DB::table('users')->where('role_id', 2)->get();
foreach($teacherUsers as $user) {
    echo "User ID: {$user->id}, Name: {$user->name}\n";
}

// List all teachers
echo "\nAll teachers in the database:\n";
$allTeachers = DB::table('teachers')->get();
foreach($allTeachers as $teacher) {
    echo "Teacher ID: {$teacher->id}, User ID: {$teacher->user_id}\n";
}

// List all subjects
echo "\nAll subjects in the database:\n";
$allSubjects = DB::table('subjects')->get();
foreach($allSubjects as $subject) {
    echo "Subject ID: {$subject->id}, Name: {$subject->name}\n";
}

// Check existing subject_teacher relationships
echo "\nExisting subject-teacher relationships:\n";
$relationships = DB::table('subject_teacher')->get();
foreach($relationships as $rel) {
    echo "Subject ID: {$rel->subject_id}, Teacher ID: {$rel->teacher_id}\n";
}

echo "\n===== END OF DIAGNOSIS =====\n";
