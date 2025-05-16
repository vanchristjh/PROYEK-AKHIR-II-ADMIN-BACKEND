<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * This file acts as a router for PHP's built-in server when using HTTPS
 */

// Set server variables if they don't exist
$_SERVER['HTTPS'] = 'on';
$_SERVER['SERVER_PORT'] = 8090;

// Forward to Laravel's public/index.php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}
require_once __DIR__.'/public/index.php';
