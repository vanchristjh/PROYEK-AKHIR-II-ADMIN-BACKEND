<?php
// Script to fix subject_teacher relationship issues and create missing records

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "===== FIXING SUBJECT_TEACHER RELATIONSHIP ISSUES =====\n\n";

try {
    DB::beginTransaction();
    
    // 1. Check if subject with ID 3 exists, if not create it
    $subject = DB::table('subjects')->where('id', 3)->first();
    if (!$subject) {
        echo "Subject with ID 3 does not exist. Creating it...\n";
        DB::table('subjects')->insert([
            'id' => 3,
            'name' => 'Fisika',
            'code' => 'FIS',
            'description' => 'Mata pelajaran Fisika',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "Subject with ID 3 created successfully.\n";
    }
    
    // 2. Check if teacher with ID 2 exists
    $teacher = DB::table('teachers')->where('id', 2)->first();
    if (!$teacher) {
        echo "Teacher with ID 2 does not exist.\n";
        
        // Check if we can create one from an existing user
        $user = DB::table('users')->where('role_id', 2)->where('id', '!=', 2)->first();
        
        if ($user) {
            echo "Found suitable user for teacher: User ID {$user->id}, Name: {$user->name}\n";
            
            // Create teacher record with ID 2
            DB::table('teachers')->insert([
                'id' => 2,
                'user_id' => $user->id,
                'nip' => '198505152010012003',
                'specialization' => 'Fisika',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "Teacher with ID 2 created successfully.\n";
        } else {
            // Create a new user and then a teacher
            echo "No suitable user found. Creating a new user and teacher...\n";
            
            // Create new user
            $userId = DB::table('users')->insertGetId([
                'role_id' => 2, // teacher role
                'name' => 'Guru Fisika',
                'username' => 'guru.fisika',
                'email' => 'guru.fisika@example.com',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Create teacher with ID 2
            DB::table('teachers')->insert([
                'id' => 2,
                'user_id' => $userId,
                'nip' => '198709212011012004',
                'specialization' => 'Fisika',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "Created new user (ID: {$userId}) and teacher with ID 2.\n";
        }
    }
    
    // 3. Verify both records exist now
    $subject = DB::table('subjects')->where('id', 3)->first();
    $teacher = DB::table('teachers')->where('id', 2)->first();
    
    if ($subject && $teacher) {
        echo "\nRecords verified:\n";
        echo "- Subject ID 3: " . $subject->name . "\n";
        echo "- Teacher ID 2: User ID " . $teacher->user_id . "\n";
        
        // 4. Final step: Check if relationship already exists
        $existingRelation = DB::table('subject_teacher')
            ->where('subject_id', 3)
            ->where('teacher_id', 2)
            ->first();
            
        if (!$existingRelation) {
            echo "\nCreating subject_teacher relationship (3,2)...\n";
            DB::table('subject_teacher')->insert([
                'subject_id' => 3,
                'teacher_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "Relationship created successfully!\n";
        } else {
            echo "\nRelationship already exists.\n";
        }
    } else {
        throw new Exception("Failed to verify created records. Cannot proceed.");
    }
    
    // Show final state
    echo "\nFinal state of database:\n";
    echo "Teachers:\n";
    foreach(DB::table('teachers')->get() as $t) {
        echo "- ID: {$t->id}, User ID: {$t->user_id}\n";
    }
    
    echo "\nSubjects:\n";
    foreach(DB::table('subjects')->get() as $s) {
        echo "- ID: {$s->id}, Name: {$s->name}\n";
    }
    
    echo "\nSubject-Teacher Relationships:\n";
    foreach(DB::table('subject_teacher')->get() as $rel) {
        echo "- Subject ID: {$rel->subject_id}, Teacher ID: {$rel->teacher_id}\n";
    }
    
    DB::commit();
    echo "\nDatabase update completed successfully!\n";
    
} catch (Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
