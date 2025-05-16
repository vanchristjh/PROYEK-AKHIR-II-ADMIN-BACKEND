<?php

// Load Laravel framework
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check the database connection
try {
    DB::connection()->getPdo();
    echo "Database connection successful. Database: " . DB::connection()->getDatabaseName() . "\n";
} catch (\Exception $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}

// Check roles table
echo "\nRoles:\n";
$roles = DB::table('roles')->get();
foreach ($roles as $role) {
    echo "- ID: {$role->id}, Name: " . ($role->name ?? 'null') . ", Slug: " . ($role->slug ?? 'null') . "\n";
}

// Check users with their roles
echo "\nUsers with roles:\n";
$users = DB::table('users')
    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
    ->select('users.id', 'users.name', 'users.username', 'users.role_id', 'roles.name as role_name', 'roles.slug as role_slug')
    ->get();

foreach ($users as $user) {
    echo "- ID: {$user->id}, Name: {$user->name}, Username: " . ($user->username ?? 'null') . ", Role ID: " . ($user->role_id ?? 'null') . ", Role: " . ($user->role_name ?? 'null') . ", Slug: " . ($user->role_slug ?? 'null') . "\n";
}

// Check middleware
echo "\nMiddleware in Kernel:\n";
$kernel = app()->make(\App\Http\Kernel::class);
echo "Route Middleware:\n";
print_r($kernel->getRouteMiddleware());
