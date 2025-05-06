<?php

require_once __DIR__ . '/vendor/autoload.php';

// Load the Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Delete the problematic migration record
$deleted = DB::table('migrations')
    ->where('migration', '2025_05_06_164147_create_personal_access_tokens_table')
    ->delete();

if ($deleted) {
    echo "Successfully removed the duplicate migration record.\n";
} else {
    echo "No migration record found for deletion.\n";
}