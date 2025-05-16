<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Fixing admin role issue...\n";

// Ensure we're using the right admin role ID
$adminRole = DB::table('roles')->where('slug', 'admin')->first();
if (!$adminRole) {
    echo "Error: Admin role not found in the database!\n";
    exit(1);
}

// Get admin users
$adminUsers = DB::table('users')->where('role_id', $adminRole->id)->get();
echo "Admin users found: " . $adminUsers->count() . "\n";

// Update the RoleMiddleware.php to include more debugging
$roleMiddlewarePath = __DIR__ . '/app/Http/Middleware/RoleMiddleware.php';
if (file_exists($roleMiddlewarePath)) {
    $roleMiddleware = file_get_contents($roleMiddlewarePath);
    
    // Add more specific logging for role matching
    $updatedContent = str_replace(
        'if ($userRole !== $role) {',
        'if ($userRole !== $role) {
            // Add more detailed logging for debugging
            \Log::warning("Role mismatch for user: " . $user->id . ", User role: " . $userRole . ", Required role: " . $role);',
        $roleMiddleware
    );
    
    file_put_contents($roleMiddlewarePath, $updatedContent);
    echo "Added more detailed logging to RoleMiddleware\n";
}

// Fix session database if needed
$sessionDriver = config('session.driver');
if ($sessionDriver === 'database') {
    echo "Session driver is set to database. Checking sessions table...\n";
    
    $hasSessionsTable = Schema::hasTable('sessions');
    echo "Sessions table exists: " . ($hasSessionsTable ? 'Yes' : 'No') . "\n";
    
    if (!$hasSessionsTable) {
        echo "Creating sessions table...\n";
        Artisan::call('session:table');
        echo Artisan::output();
        
        Artisan::call('migrate');
        echo Artisan::output();
    } else {
        // Clear old sessions that might be causing problems
        echo "Cleaning up sessions table...\n";
        DB::table('sessions')->truncate();
        echo "Sessions table cleared.\n";
    }
}

echo "Complete! Please try logging in as admin again.\n";
