<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get a teacher user
$teacher = \App\Models\User::whereHas('role', function($query) {
    $query->where('slug', 'guru');
})->first();

if (!$teacher) {
    echo "No teacher found in database\n";
    exit;
}

echo "Testing teachingClassrooms relationship for teacher: {$teacher->name}\n";

try {
    $classrooms = $teacher->teachingClassrooms;
    echo "Success! The relationship is working properly\n";
    echo "Found " . count($classrooms) . " classrooms for this teacher\n";
    
    foreach ($classrooms as $classroom) {
        echo "- {$classroom->name}\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
