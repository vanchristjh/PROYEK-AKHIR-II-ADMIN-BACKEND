<?php
// filepath: d:\SEMUA TENTANG KULIAH\SEMESTER 4\PA2\IMPLEMENTASI NEW\sman1-girsip\test_activity_trait.php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Import required classes
use App\Models\User;
use App\Models\Activity;
use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

// Create a test class to use our trait
class TestController
{
    use LogsActivity;
    
    public function testLogging()
    {
        return $this->logActivity('Test Action', 'Testing the activity logging trait', 'system', [
            'test' => true,
            'timestamp' => Carbon::now()->toIso8601String()
        ]);
    }
    
    public function testGetActivities($limit = 5)
    {
        return $this->getRecentActivities($limit);
    }
}

// Log in as an admin user for testing
$adminUser = User::whereHas('role', function($q) {
    $q->where('slug', 'admin');
})->first();

if (!$adminUser) {
    echo "⚠️ No admin user found. Please ensure you have at least one admin user.\n";
    exit(1);
}

Auth::login($adminUser);
echo "✅ Logged in as admin: {$adminUser->name} (ID: {$adminUser->id})\n";

// Create an instance of our test controller
$controller = new TestController();

// Test logging an activity
try {
    $activity = $controller->testLogging();
    
    if ($activity && $activity instanceof Activity) {
        echo "✅ Activity created with ID: {$activity->id}\n";
        echo "   - Action: {$activity->action}\n";
        echo "   - Description: {$activity->description}\n";
        echo "   - Type: {$activity->type}\n";
        echo "   - Has metadata: " . (is_array($activity->metadata) ? "Yes" : "No") . "\n";
    } else {
        echo "❌ Failed to create activity using trait\n";
    }
    
    // Test getting activities
    $activities = $controller->testGetActivities();
    echo "✅ Retrieved {$activities->count()} recent activities\n";
    
    // Test activity filtering
    $systemActivities = $controller->getRecentActivities(10, 'system');
    echo "✅ Retrieved {$systemActivities->count()} system activities\n";
    
    // Test user-specific activities
    $userActivities = $controller->getRecentActivities(10, null, $adminUser->id);
    echo "✅ Retrieved {$userActivities->count()} activities for user ID {$adminUser->id}\n";
    
} catch (\Exception $e) {
    echo "❌ Error testing activity trait: {$e->getMessage()}\n";
    echo "   In file: {$e->getFile()} at line {$e->getLine()}\n";
}

echo "\n✅ Activity trait test completed\n";
