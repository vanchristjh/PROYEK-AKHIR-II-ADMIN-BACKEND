<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get a teacher user
$teacherUser = \App\Models\User::whereHas('role', function($query) {
    $query->where('slug', 'guru');
})->first();

if (!$teacherUser) {
    echo "No teacher user found!\n";
    exit;
}

echo "Testing for teacher user ID: " . $teacherUser->id . "\n";

// Test the teacher relationship
$teacher = $teacherUser->teacher;
echo "Teacher record found: " . ($teacher ? 'Yes (ID: ' . $teacher->id . ')' : 'No') . "\n";

// If no teacher record, create one for testing
if (!$teacher) {
    echo "Creating a teacher record for testing...\n";
    $teacher = new \App\Models\Teacher();
    $teacher->user_id = $teacherUser->id;
    $teacher->nip = 'T' . str_pad($teacherUser->id, 5, '0', STR_PAD_LEFT);
    $teacher->specialization = 'General';
    $teacher->save();
    
    // Refresh user model to reflect the new teacher relationship
    $teacherUser = \App\Models\User::find($teacherUser->id);
    $teacher = $teacherUser->teacher;
    echo "Teacher record created: " . ($teacher ? 'Yes (ID: ' . $teacher->id . ')' : 'No') . "\n";
}

// Test the teacherSubjects relationship with the fix
try {
    $subjectsCount = $teacherUser->teacherSubjects()->count();
    echo "Teacher subjects count: " . $subjectsCount . "\n";
    
    $subjects = $teacherUser->teacherSubjects()->get();
    echo "Teacher subjects: \n";
    foreach ($subjects as $subject) {
        echo "- " . $subject->name . " (ID: " . $subject->id . ")\n";
    }
} catch (\Exception $e) {
    echo "Error with teacherSubjects(): " . $e->getMessage() . "\n";
}

// Test the original 'subjects' relationship with our fix
try {
    $subjectsAltCount = $teacherUser->subjects()->count();
    echo "Teacher subjects (via alias) count: " . $subjectsAltCount . "\n";
} catch (\Exception $e) {
    echo "Error with subjects() relation: " . $e->getMessage() . "\n";
}

// Test the teachingClassrooms relationship
try {
    $classroomsCount = $teacherUser->teachingClassrooms()->count();
    echo "Teaching classrooms count: " . $classroomsCount . "\n";
    
    $classrooms = $teacherUser->teachingClassrooms();
    echo "Teaching classrooms: \n";
    if ($classrooms instanceof \Illuminate\Database\Eloquent\Collection) {
        foreach ($classrooms as $classroom) {
            echo "- " . $classroom->name . " (ID: " . $classroom->id . ")\n";
        }
    } else {
        $classroomsList = $classrooms->get();
        foreach ($classroomsList as $classroom) {
            echo "- " . $classroom->name . " (ID: " . $classroom->id . ")\n";
        }
    }
} catch (\Exception $e) {
    echo "Error with teachingClassrooms(): " . $e->getMessage() . "\n";
}
