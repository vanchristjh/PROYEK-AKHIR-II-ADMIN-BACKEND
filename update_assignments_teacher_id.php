<?php
/**
 * This script updates teacher_id values in assignments table
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Update teacher_id with created_by where teacher_id is 0
$updated = DB::update('UPDATE assignments SET teacher_id = created_by WHERE teacher_id = 0');
echo "Updated $updated assignments.\n";

// Verify the update
$samples = DB::table('assignments')
    ->select('id', 'title', 'teacher_id', 'created_by')
    ->get();

echo "\nAssignments after update:\n";
foreach ($samples as $sample) {
    echo "ID: {$sample->id}, Title: {$sample->title}, Teacher ID: {$sample->teacher_id}, Created By: {$sample->created_by}\n";
}

echo "\nUpdate complete!\n";
