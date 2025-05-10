<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check if the schedules table exists and has the day column
if (Schema::hasTable('schedules')) {
    echo "Schedules table exists.\n";
    
    // Check for day column
    if (Schema::hasColumn('schedules', 'day')) {
        echo "The 'day' column exists in the schedules table.\n";
        
        // Get all columns to verify structure
        $columns = DB::select("SHOW COLUMNS FROM schedules");
        echo "Columns in schedules table:\n";
        foreach ($columns as $column) {
            echo " - {$column->Field} ({$column->Type})\n";
        }
    } else {
        echo "ERROR: The 'day' column does NOT exist in the schedules table!\n";
    }
} else {
    echo "ERROR: Schedules table does NOT exist!\n";
}

// Also check materials table since we had issues with it
if (Schema::hasTable('materials')) {
    echo "\nMaterials table exists.\n";
    
    // Get all columns
    $columns = DB::select("SHOW COLUMNS FROM materials");
    echo "Columns in materials table:\n";
    foreach ($columns as $column) {
        echo " - {$column->Field} ({$column->Type})\n";
    }
} else {
    echo "\nERROR: Materials table does NOT exist!\n";
}

// Check classroom_material table
if (Schema::hasTable('classroom_material')) {
    echo "\nClassroom_material table exists.\n";
    
    // Get all columns
    $columns = DB::select("SHOW COLUMNS FROM classroom_material");
    echo "Columns in classroom_material table:\n";
    foreach ($columns as $column) {
        echo " - {$column->Field} ({$column->Type})\n";
    }
} else {
    echo "\nERROR: Classroom_material table does NOT exist!\n";
}
