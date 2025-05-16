<?php

// This is a simple script to check session state and help debug redirect issues

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get current logged-in user
$auth = \Illuminate\Support\Facades\Auth::check();
$user = null;
$role = null;

if ($auth) {
    $user = \Illuminate\Support\Facades\Auth::user();
    $role = $user->role ? $user->role->slug : 'no-role';
}

// Get session data
$session = app('session')->all();
$sessionId = app('session')->getId();

// Remove sensitive data from session
foreach ($session as $key => $value) {
    if (in_array($key, ['_token', 'password'])) {
        $session[$key] = '[REDACTED]';
    }
}

// Get cookies
$cookies = $_COOKIE;

// Get environment settings
$envSettings = [
    'APP_URL' => env('APP_URL'),
    'SESSION_DRIVER' => env('SESSION_DRIVER'),
    'SESSION_SECURE_COOKIE' => env('SESSION_SECURE_COOKIE', false) ? 'true' : 'false',
    'SESSION_DOMAIN' => env('SESSION_DOMAIN', 'null'),
];

// Format output
$output = [
    'Authentication' => [
        'Status' => $auth ? 'Authenticated' : 'Not authenticated',
        'User ID' => $auth ? $user->id : null,
        'Username' => $auth ? $user->username : null,
        'Role' => $role,
    ],
    'Session' => [
        'ID' => $sessionId,
        'Data' => $session,
    ],
    'Cookies' => $cookies,
    'Environment' => $envSettings,
];

// Output as JSON
header('Content-Type: application/json');
echo json_encode($output, JSON_PRETTY_PRINT);
