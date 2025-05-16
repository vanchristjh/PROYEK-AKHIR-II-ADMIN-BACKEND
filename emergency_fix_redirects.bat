@echo off
echo ===================================
echo Redirect Loop Emergency Fix Tool
echo ===================================
echo.

echo Step 1: Stopping any running PHP server...
taskkill /IM php.exe /F >nul 2>&1
echo.

echo Step 2: Clearing Laravel caches...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo.

echo Step 3: Clearing session data...
if exist storage\framework\sessions (
    del /Q /F storage\framework\sessions\* >nul 2>&1
)
echo.

echo Step 4: Clearing browser instructions...
echo.
echo MANUAL BROWSER STEPS:
echo 1. Close all browser windows
echo 2. Clear browser cookies and cache:
echo    - Chrome: Press Ctrl+Shift+Del
echo    - Edge: Press Ctrl+Shift+Del
echo    - Firefox: Press Ctrl+Shift+Del
echo 3. Select "Cookies and site data" and "Cached images and files"
echo 4. Click "Clear data"
echo.
echo === EMERGENCY ACCESS INSTRUCTIONS ===
echo.
echo After clearing your browser data:
echo 1. Start the server using this batch file
echo 2. Navigate to: http://127.0.0.1:8000/emergency-login
echo 3. Login with your admin credentials
echo.
echo This will bypass any middleware that might be causing redirect loops
echo.

pause

echo Step 5: Starting Laravel server...
echo.
php artisan serve
