<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $user = App\Models\User::find(3);
    echo "Before: " . $user->avatar . "\n";
    
    $user->avatar = "test-path.jpg";
    $user->save();
    
    $user = App\Models\User::find(3);
    echo "After: " . $user->avatar . "\n";
    
    echo "Success!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
