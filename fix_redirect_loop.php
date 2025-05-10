<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

/**
 * This script diagnoses and fixes common redirect loop issues
 */
echo "Starting redirect loop diagnosis and fix...\n";

// Clear all caches
echo "Clearing Laravel caches...\n";
Artisan::call('route:clear');
echo Artisan::output();

Artisan::call('config:clear');
echo Artisan::output();

Artisan::call('cache:clear');
echo Artisan::output();

Artisan::call('view:clear');
echo Artisan::output();

// Check session configuration
echo "\nVerifying session configuration...\n";
$sessionDriver = config('session.driver');
$sessionLifetime = config('session.lifetime');
echo "Session driver: {$sessionDriver}\n";
echo "Session lifetime: {$sessionLifetime} minutes\n";

if ($sessionDriver === 'database') {
    // Check if sessions table exists
    echo "Checking for sessions table...\n";
    try {
        $tableExists = Schema::hasTable('sessions');
        echo "Sessions table exists: " . ($tableExists ? 'Yes' : 'No') . "\n";
        
        if (!$tableExists) {
            echo "Creating sessions table...\n";
            Artisan::call('session:table');
            echo Artisan::output();
            
            Artisan::call('migrate');
            echo Artisan::output();
        }
    } catch (\Exception $e) {
        echo "Error checking sessions table: " . $e->getMessage() . "\n";
    }
}

// Check for middleware conflicts
echo "\nChecking for middleware conflicts...\n";
echo "Middleware classes in use:\n";

// Check authentication middleware
$authMiddleware = app('router')->getMiddleware()['auth'] ?? null;
echo "Auth middleware: " . ($authMiddleware ?? 'Not defined') . "\n";

$guestMiddleware = app('router')->getMiddleware()['guest'] ?? null; 
echo "Guest middleware: " . ($guestMiddleware ?? 'Not defined') . "\n";

$roleMiddleware = app('router')->getMiddleware()['role'] ?? null;
echo "Role middleware: " . ($roleMiddleware ?? 'Not defined') . "\n";

// Check for duplicate routes
echo "\nChecking for duplicate routes...\n";
$routes = app('router')->getRoutes();
$routeCollection = $routes->getRoutesByName();

$loginRouteCount = 0;
foreach ($routeCollection as $name => $route) {
    if (strpos($name, 'login') !== false) {
        $loginRouteCount++;
        echo "Login route found: {$name} - {$route->uri()}\n";
        echo "Methods: " . implode(', ', $route->methods()) . "\n";
        echo "Middleware: " . implode(', ', $route->gatherMiddleware()) . "\n";
    }
}

echo "Total login routes found: {$loginRouteCount}\n";

// Done
echo "\nDiagnosis complete. If you're still experiencing redirect loops:\n";
echo "1. Clear your browser cookies\n";
echo "2. Make sure the middleware logic is correct\n";
echo "3. Check for circular redirects in authentication controllers\n";
