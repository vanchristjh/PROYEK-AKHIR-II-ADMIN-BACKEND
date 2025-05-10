<?php

// Test creating a material with classroom relationships
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Material;
use Illuminate\Support\Facades\DB;

try {
    // Start transaction
    DB::beginTransaction();
    
    // Create a test material
    $material = Material::create([
        'title' => 'Test Material',
        'description' => 'This is a test material to verify our fix',
        'subject_id' => 1, // Assuming subject with ID 1 exists
        'teacher_id' => 4, // Using the teacher ID from your error message
        'file_path' => 'test-path.pdf',
        'publish_date' => now(),
    ]);
    
    echo "Material created successfully with ID: " . $material->id . "\n";
    
    // Attach classroom(s)
    $classroomIds = [2]; // Using the classroom ID from your error message
    $material->classrooms()->attach($classroomIds);
    
    echo "Classrooms attached successfully\n";
    
    // Commit the transaction
    DB::commit();
    
    // Verify the relationship
    $attachedClassrooms = $material->classrooms()->pluck('id')->toArray();
    echo "Attached classroom IDs: " . implode(', ', $attachedClassrooms) . "\n";
    
} catch (\Exception $e) {
    // Roll back the transaction if something goes wrong
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
