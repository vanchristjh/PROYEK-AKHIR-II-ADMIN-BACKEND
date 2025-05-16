<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

$app = app();
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$columns = Schema::getColumnListing('schedules');
print_r($columns);
