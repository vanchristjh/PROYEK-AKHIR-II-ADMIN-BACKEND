<?php
// Script to fix potential redirect loops by clearing session data

// Include the Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Set up the Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Running redirect loop fix...\n";

// Clear all caches
echo "Clearing route cache...\n";
Illuminate\Support\Facades\Artisan::call('route:clear');
echo "Done\n";

echo "Clearing config cache...\n";
Illuminate\Support\Facades\Artisan::call('config:clear');
echo "Done\n";

echo "Clearing application cache...\n";
Illuminate\Support\Facades\Artisan::call('cache:clear');
echo "Done\n";

echo "Clearing view cache...\n";
Illuminate\Support\Facades\Artisan::call('view:clear');
echo "Done\n";

// Clear sessions from database
echo "Clearing all sessions from database...\n";
try {
    $db = $app->make('db');
    $deleted = $db->table('sessions')->delete();
    echo "Deleted $deleted sessions\n";
} catch (Exception $e) {
    echo "Error clearing sessions: " . $e->getMessage() . "\n";
}

echo "\nRedirect loop fix completed. Please restart your application and login again.\n";
