<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Use the same code from GuruDashboardController that was causing the error
try {
    $guru = \App\Models\User::find(2); // The teacher user
    
    if (!$guru) {
        echo "User not found.\n";
        exit;
    }
    
    echo "Testing teacherSubjects() method for user {$guru->id}...\n";
    
    // This is the line that was causing the error
    $subjectsCount = $guru->teacherSubjects()->count();
    echo "Success! Subject count: {$subjectsCount}\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
