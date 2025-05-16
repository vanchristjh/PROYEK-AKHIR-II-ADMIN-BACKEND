<?php
// Script to test the subject-teacher relationship

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "===== TESTING SUBJECT-TEACHER RELATIONSHIP =====\n\n";

try {
    // Use a transaction to make sure we don't mess up the database
    DB::beginTransaction();
    
    // Get the first teacher
    $teacher = DB::table('teachers')->first();
    if (!$teacher) {
        throw new Exception("No teachers found in the database");
    }
    
    // Get the first subject
    $subject = DB::table('subjects')->first();
    if (!$subject) {
        throw new Exception("No subjects found in the database");
    }
    
    echo "Found teacher with ID: {$teacher->id}\n";
    echo "Found subject with ID: {$subject->id}\n\n";
    
    // Check if relationship exists
    $existingRelation = DB::table('subject_teacher')
        ->where('subject_id', $subject->id)
        ->where('teacher_id', $teacher->id)
        ->first();
    
    if ($existingRelation) {
        echo "Relationship already exists between subject {$subject->id} and teacher {$teacher->id}\n";
    } else {
        // Create the relationship
        echo "Creating relationship between subject {$subject->id} and teacher {$teacher->id}...\n";
        
        DB::table('subject_teacher')->insert([
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "Successfully created relationship!\n";
    }
    
    // Show all subject_teacher relationships after our changes
    echo "\nAll subject_teacher relationships:\n";
    $allRelations = DB::table('subject_teacher')->get();
    foreach ($allRelations as $relation) {
        echo "Subject ID: {$relation->subject_id}, Teacher ID: {$relation->teacher_id}\n";
    }
    
    // Commit the transaction if everything worked
    DB::commit();
    
} catch (Exception $e) {
    // Rollback in case of error
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
