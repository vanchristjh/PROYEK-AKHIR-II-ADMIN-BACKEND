<?php
// Script to test the SubjectTeacherController

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\SubjectTeacherController;
use Illuminate\Http\Request;

echo "===== TESTING SubjectTeacherController::assignTeacher =====\n\n";

// Create a mock request with the teacher and subject IDs
$request = new Request();
$request->replace([
    'subject_id' => 2, // Use the subject ID we created earlier
    'teacher_id' => 1, // Use the teacher ID we created earlier
]);

try {
    // First, let's check if these records exist in the database
    $subject = DB::table('subjects')->find(2);
    $teacher = DB::table('teachers')->find(1);
    
    echo "Checking if subject with ID 2 exists: " . ($subject ? "Yes" : "No") . "\n";
    echo "Checking if teacher with ID 1 exists: " . ($teacher ? "Yes" : "No") . "\n\n";
    
    // Create an instance of the controller
    $controller = new SubjectTeacherController();
    
    // Call the assignTeacher method
    echo "Calling SubjectTeacherController::assignTeacher...\n";
    $response = $controller->assignTeacher($request);
    
    // Check the response
    echo "Response status: " . $response->getStatusCode() . "\n";
    echo "Response session contains: \n";
    $session = app('session.store');
    foreach(['success', 'error', 'info'] as $key) {
        if ($session->has($key)) {
            echo "- {$key}: " . $session->get($key) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
