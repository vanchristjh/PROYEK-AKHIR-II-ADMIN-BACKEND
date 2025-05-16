<?php
// Fix teacher records in the database

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "Starting teacher record fix script...\n";

// 1. Check current state
$currentTeachers = User::where('role_id', 2)->count();
echo "Current teachers with role_id=2: $currentTeachers\n";

// Look for potential teachers
$potentialTeachers = User::where(function($query) {
        $query->whereNull('role_id')
            ->orWhere('role_id', '!=', 2);
    })
    ->where(function($query) {
        $query->whereNotNull('nip')
            ->orWhere('email', 'like', '%guru%')
            ->orWhere('email', 'like', '%teacher%')
            ->orWhere('name', 'like', '%guru%')
            ->orWhere('name', 'like', '%teacher%');
    })
    ->get();

$potentialCount = $potentialTeachers->count();
echo "Found $potentialCount potential teachers with incorrect role_id\n";

// 2. Fix teacher records if needed
if ($potentialCount > 0 && $currentTeachers == 0) {
    echo "Proceeding to update role_id for potential teachers...\n";
    
    DB::beginTransaction();
    try {
        $updatedCount = 0;
        
        foreach ($potentialTeachers as $teacher) {
            echo "Updating teacher: {$teacher->name} (ID: {$teacher->id})\n";
            
            $teacher->role_id = 2; // Set role_id to teacher role
            $teacher->save();
            
            $updatedCount++;
        }
        
        DB::commit();
        echo "Successfully updated $updatedCount teacher records\n";
    } catch (\Exception $e) {
        DB::rollBack();
        echo "Error updating teacher records: " . $e->getMessage() . "\n";
    }
} elseif ($currentTeachers > 0) {
    echo "There are already $currentTeachers teachers with correct role_id. No updates needed.\n";
} else {
    echo "No potential teachers found to update.\n";
}

// 3. Verify the fix worked
$updatedTeachers = User::where('role_id', 2)->count();
echo "Updated count of teachers with role_id=2: $updatedTeachers\n";

echo "Script completed.\n";
