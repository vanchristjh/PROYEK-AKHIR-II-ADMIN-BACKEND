<?php
$migrationPath = "database/migrations";
$files = glob($migrationPath . "/*.php");
$classes = [];
$duplicates = [];

foreach ($files as $file) {
    $content = file_get_contents($file);
    if (preg_match("/class\s+([a-zA-Z0-9_]+)\s+extends\s+Migration/i", $content, $matches)) {
        $className = $matches[1];
        if (isset($classes[$className])) {
            if (!isset($duplicates[$className])) {
                $duplicates[$className] = [$classes[$className]];
            }
            $duplicates[$className][] = $file;
        } else {
            $classes[$className] = $file;
        }
    }
}

echo "Duplicate migration classes found:\n";
if (empty($duplicates)) {
    echo "No duplicate migration classes found.\n";
} else {
    foreach ($duplicates as $className => $files) {
        echo "$className:\n";
        foreach ($files as $file) {
            echo "  - $file\n";
        }
    }
}

