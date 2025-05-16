@echo off
echo ===========================
echo LARAVEL AUTH DEBUG UTILITY
echo ===========================
echo.

:: Clear all Laravel caches
echo Clearing Laravel caches...
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan optimize:clear

:: Fix sessions table if needed
echo.
echo Checking sessions table...
php -r "require 'vendor/autoload.php'; $app = require_once __DIR__ . '/bootstrap/app.php'; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); echo \Illuminate\Support\Facades\Schema::hasTable('sessions') ? 'Sessions table exists.' : 'Creating sessions table...'; if (!\Illuminate\Support\Facades\Schema::hasTable('sessions')) { \Illuminate\Support\Facades\Artisan::call('session:table'); \Illuminate\Support\Facades\Artisan::call('migrate'); echo 'Done.'; } else { echo ' Clearing sessions...; \Illuminate\Support\Facades\DB::table(\'sessions\')->truncate(); echo 'Done.'; }"

:: Instructions for browser
echo.
echo ==============================================
echo INSTRUCTIONS TO FIX ADMIN LOGIN REDIRECT LOOP
echo ==============================================
echo 1. Close all browser windows/tabs
echo 2. Open browser and clear cookies/browser data
echo 3. Try logging in again
echo.
echo If you still experience issues:
echo - Try using Incognito/Private browsing mode
echo - Check routes/web.php for conflicting routes
echo - Check for middleware conflicts in app/Http/Kernel.php
echo ==============================================
echo.

pause
