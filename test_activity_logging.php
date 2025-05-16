<?php
// Quick test script to ensure activity logging is working

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Import required classes
use App\Models\Activity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

// Test activity logging via the model
try {
    $activity = Activity::create([
        'user_id' => $adminUser->id,
        'action' => 'Test Activity',
        'description' => 'Testing the activity logging system',
        'type' => 'system',
        'ip_address' => '127.0.0.1',
        'user_agent' => 'PHP Test Script',
        'metadata' => ['test' => true, 'timestamp' => Carbon::now()->toIso8601String()]
    ]);
    
    echo "✅ Activity created with ID: {$activity->id}\n";
    
    // Verify activity exists by querying
    $foundActivity = Activity::find($activity->id);
    if ($foundActivity) {
        echo "✅ Successfully verified activity retrieval\n";
        echo "   - Action: {$foundActivity->action}\n";
        echo "   - Description: {$foundActivity->description}\n";
        echo "   - Type: {$foundActivity->type}\n";
        echo "   - Created at: {$foundActivity->created_at->format('Y-m-d H:i:s')}\n";
        
        if ($foundActivity->user) {
            echo "   - User: {$foundActivity->user->name}\n";
        }
    } else {
        echo "❌ Failed to find the created activity\n";
    }
    
    // Test the relationship
    $userActivities = $adminUser->activities;
    if ($userActivities && $userActivities->count() > 0) {
        echo "✅ User->activities relationship is working\n";
        echo "   - User has {$userActivities->count()} logged activities\n";
    } else {
        echo "❌ User->activities relationship not working or no activities found\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error creating activity: {$e->getMessage()}\n";
    echo "   In file: {$e->getFile()} at line {$e->getLine()}\n";
}

echo "\n✅ Activity test script completed\n";
