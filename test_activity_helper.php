<?php
// filepath: d:\SEMUA TENTANG KULIAH\SEMESTER 4\PA2\IMPLEMENTASI NEW\sman1-girsip\test_activity_helper.php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Import our helper
use App\Helpers\ActivityIconHelper;

echo "Testing ActivityIconHelper...\n\n";

// Test icon mapping
$testTypes = ['login', 'user_created', 'system', 'assignment', 'announcement', 'unknown_type'];

foreach ($testTypes as $type) {
    echo "Type: {$type}\n";
    echo "- Icon: " . ActivityIconHelper::getIcon($type) . "\n";
    echo "- Color: " . ActivityIconHelper::getColor($type) . "\n\n";
}

// Display the full mappings
echo "All icon mappings:\n";
print_r(ActivityIconHelper::getIconMap());

echo "\nAll color mappings:\n";
print_r(ActivityIconHelper::getColorMap());

echo "\n✅ ActivityIconHelper test completed\n";
