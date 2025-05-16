<?php
/**
 * This script checks for potential issues in the middleware configuration
 * that might cause redirect loops.
 */
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking for middleware conflicts...\n";

$kernel = $app->make(\App\Http\Kernel::class);
$routeMiddleware = $kernel->getRouteMiddleware();

// Check for duplicate role middleware
echo "Checking for duplicate middleware definitions...\n";
$roleMiddleware = array_filter($routeMiddleware, function($key) {
    return $key === 'role';
}, ARRAY_FILTER_USE_KEY);

if (count($roleMiddleware) > 0) {
    echo "Role middleware found: " . implode(", ", array_keys($roleMiddleware)) . "\n";
    
    foreach ($roleMiddleware as $key => $class) {
        echo "  $key => $class\n";
    }
}

// Check middleware aliases
echo "\nChecking middleware aliases...\n";
$aliases = $kernel->getMiddlewareAliases();
$roleAliases = array_filter($aliases, function($key) {
    return $key === 'role';
}, ARRAY_FILTER_USE_KEY);

if (count($roleAliases) > 0) {
    echo "Role middleware aliases found: " . implode(", ", array_keys($roleAliases)) . "\n";
    
    foreach ($roleAliases as $key => $class) {
        echo "  $key => $class\n";
    }
}

// Check for redirect loop in the RedirectIfAuthenticated middleware
echo "\nChecking for redirect loop in RedirectIfAuthenticated middleware...\n";
$redirectIfAuth = \App\Http\Middleware\RedirectIfAuthenticated::class;
$reflector = new ReflectionClass($redirectIfAuth);
$method = $reflector->getMethod('handle');
$contents = file_get_contents($reflector->getFileName());

if (strpos($contents, 'redirect()->route') !== false && 
    strpos($contents, 'admin.dashboard') !== false) {
    echo "Found potential redirect routes in RedirectIfAuthenticated:\n";
    preg_match_all('/redirect\(\)->route\([\'"]([^\'"]+)[\'"]\)/', $contents, $matches);
    if (isset($matches[1])) {
        foreach ($matches[1] as $route) {
            echo "  - $route\n";
        }
    }
}

// Output environment settings
echo "\nChecking environment settings...\n";
echo "APP_URL: " . env('APP_URL') . "\n";
echo "SESSION_DRIVER: " . env('SESSION_DRIVER') . "\n";
echo "SESSION_DOMAIN: " . env('SESSION_DOMAIN', 'null') . "\n";
echo "SESSION_SECURE_COOKIE: " . (env('SESSION_SECURE_COOKIE', false) ? 'true' : 'false') . "\n";

// Print done
echo "\nCheck completed. If you found any issues, please fix them and try logging in again.\n";
