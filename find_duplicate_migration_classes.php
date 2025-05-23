<?php
$migrationPath = 'database/migrations';
$files = glob($migrationPath . '/*.php');
$classes = [];
$duplicates = [];

foreach ($files as $file) {
    $content = file_get_contents($file);
    if (preg_match('/class\s+([a-zA-Z0-9_]+)\s+extends\s+Migration/i', $content, $matches)) {
        $className = $matches[1];
        if (isset($classes[$className])) {
            $duplicates[$className][] = $file;
            $duplicates[$className][] = $classes[$className];
        } else {
            $classes[$className] = $file;
        }
    }
}

echo 'Duplicate migration classes found:' . PHP_EOL;
foreach ($duplicates as $className => $files) {
    echo $className . ':' . PHP_EOL;
    foreach ($files as $file) {
        echo '  - ' . $file . PHP_EOL;
    }
}

