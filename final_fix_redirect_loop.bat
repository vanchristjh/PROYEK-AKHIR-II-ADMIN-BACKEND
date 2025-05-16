@echo off
echo ====================================================
echo LARAVEL REDIRECT LOOP FIXER - FINAL VERSION
echo ====================================================
echo.

cd /d "D:\SEMUA TENTANG KULIAH\SEMESTER 4\PA2\IMPLEMENTASI NEW\sman1-girsip"

echo STEP 1: Fixing environment variables...
echo SESSION_DRIVER=database >> .env
echo SESSION_LIFETIME=120 >> .env
echo SESSION_SECURE_COOKIE=false >> .env
echo SESSION_DOMAIN=null >> .env
echo.

echo STEP 2: Clearing all Laravel caches...
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan optimize:clear
echo All caches cleared.
echo.

echo STEP 3: Fixing database sessions...
php artisan session:table
php artisan migrate
echo.

echo STEP 4: Clearing session data...
php -r "require 'vendor/autoload.php'; $app = require_once 'bootstrap/app.php'; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); if (Schema::hasTable('sessions')) { DB::table('sessions')->truncate(); echo 'Sessions cleared\n'; }"
echo.

echo STEP 5: Starting Laravel server with correct settings...
echo.
php -d max_execution_time=1800 ^
    -d memory_limit=512M ^
    -d upload_max_filesize=20M ^
    -d post_max_size=21M ^
    -d session.cookie_secure=0 ^
    -d display_errors=On ^
    artisan serve --port=8090 --host=localhost
