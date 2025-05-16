<?php

use Illuminate\Support\Facades\Route;

// Check materials table columns
Route::get('/check-materials-columns', function () {
    $columns = \Schema::getColumnListing('materials');
    
    $hasAudienceColumn = in_array('audience', $columns);
    
    return [
        'columns' => $columns,
        'has_audience_column' => $hasAudienceColumn
    ];
});
