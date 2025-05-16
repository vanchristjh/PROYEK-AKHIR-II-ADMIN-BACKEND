<?php
// filepath: d:\SEMUA TENTANG KULIAH\SEMESTER 4\PA2\IMPLEMENTASI NEW\sman1-girsip\test_renderer.php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Import required classes
use App\Helpers\ActivityIconHelper;
use App\Helpers\ActivityRenderer;
use App\Models\User;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

echo "Testing ActivityRenderer...\n\n";

// Find an admin user
$adminUser = User::whereHas('role', function($q) {
    $q->where('slug', 'admin');
})->first();

if (!$adminUser) {
    echo "⚠️ No admin user found. Please ensure you have at least one admin user.\n";
    exit(1);
}

Auth::login($adminUser);

// Create a test activity
$activity = Activity::create([
    'user_id' => $adminUser->id,
    'action' => 'Test Renderer',
    'description' => 'Testing the ActivityRenderer',
    'type' => 'system',
    'ip_address' => '127.0.0.1',
    'user_agent' => 'PHP Test Script',
    'metadata' => ['test' => true, 'timestamp' => Carbon::now()->toIso8601String()]
]);

echo "✅ Created test activity with ID: {$activity->id}\n\n";

// Verify icon helper
$icon = ActivityIconHelper::getIcon($activity->type);
$color = ActivityIconHelper::getColor($activity->type);

echo "🔍 Activity type: {$activity->type}\n";
echo "🔍 Icon: {$icon}\n";
echo "🔍 Color: {$color}\n\n";

try {
    // Try rendering the activity
    $html = ActivityRenderer::render($activity, 0);
    
    echo "✅ Renderer generated HTML successfully!\n";
    echo "HTML output is too large to display fully, but here's a preview:\n\n";
    echo substr($html, 0, 300) . "...\n\n";
    
    // Verify specific elements in the HTML
    if (strpos($html, "from-{$color}-400 to-{$color}-600") !== false) {
        echo "✅ Found correct color: {$color}\n";
    } else {
        echo "❌ Color {$color} not found in output\n";
    }
    
    if (strpos($html, "fa-{$icon}") !== false) {
        echo "✅ Found correct icon: {$icon}\n";
    } else {
        echo "❌ Icon {$icon} not found in output\n";
    }
    
    if (strpos($html, $activity->description) !== false) {
        echo "✅ Found activity description in output\n";
    } else {
        echo "❌ Activity description not found in output\n";
    }
    
    if (strpos($html, $adminUser->name) !== false) {
        echo "✅ Found user name in output\n";
    } else {
        echo "❌ User name not found in output\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error using renderer: " . $e->getMessage() . "\n";
    echo "In file: {$e->getFile()} at line {$e->getLine()}\n";
}

echo "\n✅ Renderer test completed\n";
