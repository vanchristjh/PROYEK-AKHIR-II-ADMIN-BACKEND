<?php
// This PHP script will create teacher records for users with role_id=2

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Start a transaction
DB::beginTransaction();

try {
    // Get all users with role_id=2 (teachers)
    $teacherUsers = DB::table('users')->where('role_id', 2)->get();
    
    echo "Found " . count($teacherUsers) . " users with teacher role.\n";
    
    // Create teacher records for each user
    foreach ($teacherUsers as $user) {
        // Check if teacher record already exists
        $existingTeacher = DB::table('teachers')->where('user_id', $user->id)->first();
        
        if (!$existingTeacher) {
            // Create a new teacher record
            DB::table('teachers')->insert([
                'user_id' => $user->id,
                'nip' => null, // You can set a default NIP if needed
                'specialization' => null, // You can set a default specialization if needed
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            echo "Created teacher record for user: {$user->name} (ID: {$user->id})\n";
        } else {
            echo "Teacher record already exists for user: {$user->name} (ID: {$user->id})\n";
        }
    }
    
    // Check if we have any subjects, if not create a sample one
    $subjects = DB::table('subjects')->get();
    
    if (count($subjects) == 0) {
        DB::table('subjects')->insert([
            'name' => 'Matematika',
            'code' => 'MTK',
            'description' => 'Mata pelajaran Matematika',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        echo "Created sample subject 'Matematika'\n";
    } else {
        echo "Found " . count($subjects) . " existing subjects.\n";
    }
    
    // Commit the transaction
    DB::commit();
    
    echo "Database setup completed successfully.\n";
    
    // Show the current state
    echo "\nCurrent teachers:\n";
    $teachers = DB::table('teachers')->get();
    foreach ($teachers as $teacher) {
        $user = DB::table('users')->where('id', $teacher->user_id)->first();
        echo "Teacher ID: {$teacher->id}, User: {$user->name}, User ID: {$user->id}\n";
    }
    
    echo "\nCurrent subjects:\n";
    $subjects = DB::table('subjects')->get();
    foreach ($subjects as $subject) {
        echo "Subject ID: {$subject->id}, Name: {$subject->name}\n";
    }
    
} catch (Exception $e) {
    // Rollback in case of error
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
