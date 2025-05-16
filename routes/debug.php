<?php

use Illuminate\Support\Facades\Route;

// Add a temporary route for navigation debugging
Route::get('/nav-test', function () {
    return view('nav-links');
});
