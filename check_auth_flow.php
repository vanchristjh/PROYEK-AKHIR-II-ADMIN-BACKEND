<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking authentication flow...\n";

// Test the authentication flow
echo "1. Simulating guest access to login page...\n";
$request = Request::create('/login', 'GET');
$kernel = app()->make(\Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request);
echo "Response status: " . $response->getStatusCode() . "\n";

echo "\n2. Simulating authenticated access to login page...\n";
// Create a mock user
$user = \App\Models\User::whereHas('role', function($query) {
    $query->where('slug', 'guru');
})->first();

if (!$user) {
    echo "No teacher user found for testing!\n";
    exit;
}

// Log the user in
Auth::login($user);
echo "Logged in as user ID: " . $user->id . " with role: " . $user->role->slug . "\n";

// Now test accessing the login page while authenticated
$request = Request::create('/login', 'GET');
$response = $kernel->handle($request);
echo "Redirection present: " . ($response->isRedirection() ? 'Yes' : 'No') . "\n";
if ($response->isRedirection()) {
    echo "Redirect target: " . $response->headers->get('Location') . "\n";
}

// Clean up
Auth::logout();
echo "\nTests completed.\n";
