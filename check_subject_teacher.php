<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the database connection
$pdo = DB::connection()->getPdo();

// Get the table structure
$stmt = $pdo->query("SHOW COLUMNS FROM subject_teacher");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Print the column information
foreach ($columns as $column) {
    echo $column['Field'] . ' - ' . $column['Type'] . PHP_EOL;
}

// Check if specific columns exist
$hasTeacherId = false;
$hasUserId = false;

foreach ($columns as $column) {
    if ($column['Field'] === 'teacher_id') {
        $hasTeacherId = true;
    }
    if ($column['Field'] === 'user_id') {
        $hasUserId = true;
    }
}

echo "Has teacher_id: " . ($hasTeacherId ? "Yes" : "No") . PHP_EOL;
echo "Has user_id: " . ($hasUserId ? "Yes" : "No") . PHP_EOL;
