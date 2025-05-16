<?php
// Simple count of teachers

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

// Count teachers
$teacherCount = User::where('role_id', 2)->count();
echo "Teachers with role_id=2: " . $teacherCount . "\n";

// List first few teachers
$teachers = User::where('role_id', 2)->get(['id', 'name', 'email', 'role_id']);
echo "Teacher records:\n";
foreach ($teachers as $teacher) {
    echo "- ID: {$teacher->id}, Name: {$teacher->name}, Email: {$teacher->email}, Role ID: {$teacher->role_id}\n";
}

// Count all users
$totalUsers = User::count();
echo "Total user records: " . $totalUsers . "\n";

// Get role distribution
$roles = User::selectRaw('role_id, COUNT(*) as count')
    ->groupBy('role_id')
    ->get();

echo "Role distribution:\n";
foreach ($roles as $role) {
    echo "- Role ID: " . ($role->role_id ?? 'NULL') . ", Count: {$role->count}\n";
}
