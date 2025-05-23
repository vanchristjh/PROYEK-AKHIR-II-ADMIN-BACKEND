@echo off
echo Clearing Laravel caches...
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan optimize:clear

echo Fixing session configuration...
php artisan session:table
php artisan migrate

echo Done! Please try logging in again.
pause
