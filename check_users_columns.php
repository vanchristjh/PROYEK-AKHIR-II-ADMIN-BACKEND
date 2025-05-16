<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get users table columns
$columns = Illuminate\Support\Facades\Schema::getColumnListing('users');
echo "Users table columns:\n";
print_r($columns);

// Check if specific columns exist
echo "\nDoes classroom_id exist? " . (in_array('classroom_id', $columns) ? 'Yes' : 'No');
echo "\nDoes role_id exist? " . (in_array('role_id', $columns) ? 'Yes' : 'No') . "\n";

// Show table schema details
$pdo = Illuminate\Support\Facades\DB::connection()->getPdo();
$stmt = $pdo->query("SHOW CREATE TABLE users");
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo "\nTable schema:\n";
print_r($result['Create Table'] ?? $result);
