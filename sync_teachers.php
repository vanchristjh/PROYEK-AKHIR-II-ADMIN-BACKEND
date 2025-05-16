<?php
// Script to synchronize teachers from users table to teachers table

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;

// Step 1: Find all users with role_id = 2 (teachers)
$teacherUsers = User::where('role_id', 2)->get();
echo "Found " . $teacherUsers->count() . " teachers in the users table.\n\n";

if ($teacherUsers->count() == 0) {
    echo "No teachers found in the users table. Nothing to sync.\n";
    exit;
}

// Step 2: For each teacher user, create a corresponding record in the teachers table
$syncCount = 0;
foreach ($teacherUsers as $user) {
    // Check if a record already exists for this user in the teachers table
    $existingRecord = DB::table('teachers')->where('user_id', $user->id)->first();
    
    if ($existingRecord) {
        echo "Teacher record already exists for user {$user->name} (ID: {$user->id})\n";
        continue;
    }
    
    // Create a new teacher record
    try {
        DB::table('teachers')->insert([
            'name' => $user->name,
            'user_id' => $user->id,
            'nip' => $user->nip ?? null,  // Assuming nip might be stored in the users table
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        echo "Created teacher record for {$user->name} (ID: {$user->id})\n";
        $syncCount++;
    } catch (Exception $e) {
        echo "Error creating teacher record for user ID {$user->id}: {$e->getMessage()}\n";
    }
}

echo "\nSynchronization complete. Created $syncCount new teacher records.\n";

// Step 3: Verify
$teacherRecords = DB::table('teachers')->count();
echo "\nVerification: Now there are $teacherRecords records in the teachers table.\n";
