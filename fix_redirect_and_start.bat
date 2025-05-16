@echo off
echo ===================================
echo Fix for ERR_TOO_MANY_REDIRECTS Issue
echo ===================================
echo.
echo This batch file will help resolve the redirect loop issue in your Laravel application.
echo.

echo Step 1: Clearing Laravel cache files...
cd /d "D:\SEMUA TENTANG KULIAH\SEMESTER 4\PA2\IMPLEMENTASI NEW\sman1-girsip"
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan optimize:clear
echo.

echo Step 2: Clearing session data...
php artisan session:flush
echo.

echo ===================================
echo MANUAL BROWSER STEPS:
echo ===================================
echo 1. Close all browser windows
echo 2. Clear browser cookies and cache:
echo    - Chrome: Press Ctrl+Shift+Del
echo    - Edge: Press Ctrl+Shift+Del
echo    - Firefox: Press Ctrl+Shift+Del
echo 3. Select "Cookies and site data" and "Cached images and files"
echo 4. Click "Clear data"
echo 5. Restart your browser and try logging in again
echo ===================================
echo.

echo PHP Server will be started with HTTP (not HTTPS) to avoid redirect issues
echo Press any key to start the server...
pause >nul

echo Starting Laravel server...
php -d max_execution_time=1800 -d memory_limit=512M -d upload_max_filesize=20M -d post_max_size=21M -d default_socket_timeout=1800 -d display_errors=On artisan serve --port=8090 --host=localhost
