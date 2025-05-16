<?php
// Test Schedule-Teacher relationship

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\User;

// Step 1: Verify teacher exists in both users and teachers tables
$user = User::where('role_id', 2)->first();
$teacher = Teacher::where('user_id', $user->id)->first();

echo "User teacher: ID = {$user->id}, Name = {$user->name}\n";
echo "Teacher model: " . ($teacher ? "Found (ID = {$teacher->id}, user_id = {$teacher->user_id})" : "Not found") . "\n";

// Step 2: Test creating a schedule with this teacher
try {
    // Just prepare the data but don't save to avoid modifying the database
    $schedule = new Schedule();
    $schedule->teacher_id = $teacher->id;
    $schedule->classroom_id = 1; // Assuming a classroom with ID 1 exists
    $schedule->subject_id = 1;   // Assuming a subject with ID 1 exists
    $schedule->day = 'Senin';
    $schedule->start_time = '08:00';
    $schedule->end_time = '09:00';
    $schedule->school_year = '2023/2024';
    
    echo "\nSchedule data ready for creation:\n";
    echo "- teacher_id: {$schedule->teacher_id}\n";
    echo "- classroom_id: {$schedule->classroom_id}\n";
    echo "- subject_id: {$schedule->subject_id}\n";
    echo "- day: {$schedule->day}\n";
    echo "- times: {$schedule->start_time} - {$schedule->end_time}\n";
    echo "- school_year: {$schedule->school_year}\n";
    
    echo "\nThis schedule should now pass validation.\n";
} catch (Exception $e) {
    echo "Error preparing schedule: {$e->getMessage()}\n";
}
