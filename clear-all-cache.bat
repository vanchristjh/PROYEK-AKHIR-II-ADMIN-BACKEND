@echo off
echo Clearing Laravel route cache...
php artisan route:clear

echo Clearing Laravel config cache...
php artisan config:clear

echo Clearing Laravel application cache...
php artisan cache:clear

echo Clearing Laravel view cache...
php artisan view:clear

echo Optimizing Laravel...
php artisan optimize

echo Done!
