<?php

// This is a simple file to test if your routes are accessible
echo '<h1>Route Test Page</h1>';
echo '<p>If you can see this, your PHP is working correctly.</p>';
echo '<hr>';
echo '<h2>Testing Materials Route:</h2>';

// Print the URL that should work
echo '<p>The URL for materials should be: ' . url('/siswa/materials') . '</p>';
echo '<a href="' . url('/siswa/materials') . '">Try direct link to materials page</a>';

// Show server info for debugging
echo '<hr>';
echo '<h2>Server Information:</h2>';
echo '<pre>';
print_r($_SERVER);
echo '</pre>';
