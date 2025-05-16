@echo off
setlocal enabledelayedexpansion

echo ====================================================
echo LARAVEL REDIRECT LOOP DIAGNOSTIC AND FIX TOOL
echo ====================================================
echo.

cd /d "D:\SEMUA TENTANG KULIAH\SEMESTER 4\PA2\IMPLEMENTASI NEW\sman1-girsip"

echo Step 1: Checking environment configuration...
echo ------------------------------------
php -r "require 'vendor/autoload.php'; $app = require_once 'bootstrap/app.php'; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); echo 'APP_URL: ' . env('APP_URL') . PHP_EOL; echo 'SESSION_SECURE_COOKIE: ' . (env('SESSION_SECURE_COOKIE') ? 'true' : 'false') . PHP_EOL; echo 'SESSION_DOMAIN: ' . env('SESSION_DOMAIN', 'null') . PHP_EOL;"
echo.

echo Step 2: Checking for middleware conflicts...
echo ------------------------------------
php check_middleware_conflicts.php
echo.

echo Step 3: Checking database session table...
echo ------------------------------------
php -r "require 'vendor/autoload.php'; $app = require_once 'bootstrap/app.php'; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); if (!Schema::hasTable('sessions')) { echo 'Sessions table not found! Creating...' . PHP_EOL; Artisan::call('session:table'); Artisan::call('migrate'); echo Artisan::output(); } else { echo 'Sessions table exists.' . PHP_EOL; echo 'Clearing old sessions...' . PHP_EOL; DB::table('sessions')->truncate(); echo 'Done.' . PHP_EOL; }"
echo.

echo Step 4: Clearing Laravel caches...
echo ------------------------------------
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan optimize:clear
echo All caches cleared.
echo.

echo Step 5: Checking for redirect loops in routes...
echo ------------------------------------
php -r "require 'vendor/autoload.php'; $app = require_once 'bootstrap/app.php'; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); $routes = Route::getRoutes()->getRoutes(); $redirectRoutes = []; foreach ($routes as $route) { if (strpos($route->uri, 'login') !== false || strpos($route->uri, 'admin') !== false) { echo 'Route: ' . $route->uri . ' [' . implode(', ', $route->methods) . ']' . PHP_EOL; echo '  Middleware: ' . implode(', ', $route->middleware()) . PHP_EOL; } }"
echo.

echo ====================================================
echo FIX RECOMMENDATIONS
echo ====================================================
echo.
echo Based on common issues with redirect loops, please:
echo.
echo 1. Make sure your browser cookies are cleared:
echo    - Close all browser windows
echo    - Open a new window and clear cookies (Ctrl+Shift+Del)
echo    - Select all cookies and site data and clear them
echo.
echo 2. Try using a private/incognito browser window
echo.
echo 3. If using Chrome, try another browser like Firefox or Edge
echo.
echo 4. Check the Laravel log file for detailed debug info:
echo    - Look in storage/logs/laravel.log
echo.
echo 5. Visit the debug URL to check authentication status:
echo    - http://localhost:8090/debug-auth
echo.

echo Would you like to start the Laravel server now? (Y/N)
set /p start_server=

if /i "%start_server%"=="Y" (
    echo.
    echo Starting Laravel server...
    echo.
    php -d max_execution_time=1800 ^
        -d memory_limit=512M ^
        -d upload_max_filesize=20M ^
        -d post_max_size=21M ^
        -d default_socket_timeout=1800 ^
        -d session.cookie_secure=0 ^
        -d display_errors=On ^
        artisan serve --port=8090 --host=localhost
) else (
    echo.
    echo You can start the server manually using:
    echo    php artisan serve --port=8090
    echo.
    pause
)

endlocal
