<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
$columns = DB::select("SHOW COLUMNS FROM assignments");
echo "Assignments columns:\n";
foreach ($columns as $column) {
    echo $column->Field . "\n";
}

