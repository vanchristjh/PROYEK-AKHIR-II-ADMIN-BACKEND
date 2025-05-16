<?php
// Simple script to check if teachers exist in the database

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Count teachers (role_id = 2)
$teachersCount = \App\Models\User::where('role_id', 2)->count();
echo "Number of teachers found: " . $teachersCount . PHP_EOL;

// List the first 5 teachers
$teachers = \App\Models\User::where('role_id', 2)->take(5)->get(['id', 'name', 'email']);
echo "First 5 teachers:" . PHP_EOL;
foreach ($teachers as $teacher) {
    echo "ID: {$teacher->id}, Name: {$teacher->name}, Email: {$teacher->email}" . PHP_EOL;
}

// Check if there's any issue with the role_id field
$distinctRoles = \App\Models\User::distinct()->pluck('role_id')->toArray();
echo "Distinct role IDs in the database: " . implode(', ', $distinctRoles) . PHP_EOL;

// Look for potential teacher records with incorrect role_id
$potentialTeachers = \App\Models\User::whereNull('role_id')
    ->orWhere('role_id', '!=', 2)
    ->whereNotNull('nip')
    ->take(5)
    ->get(['id', 'name', 'role_id', 'nip']);

echo "Potential teachers with incorrect role_id:" . PHP_EOL;
foreach ($potentialTeachers as $teacher) {
    echo "ID: {$teacher->id}, Name: {$teacher->name}, Role ID: {$teacher->role_id}, NIP: {$teacher->nip}" . PHP_EOL;
}

echo "Script completed." . PHP_EOL;
