<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking teachers table:\n";
$teachers = DB::table('teachers')->get();
print_r($teachers);

echo "\nChecking if teacher ID 2 exists:\n";
$teacher = DB::table('teachers')->where('id', 2)->first();
var_dump($teacher);

echo "\nChecking if subject ID 1 exists:\n";
$subject = DB::table('subjects')->where('id', 1)->first();
var_dump($subject);
