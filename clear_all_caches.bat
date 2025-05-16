@echo off
echo Membersihkan cache Laravel...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

echo Membuat direktori view baru untuk refresh...
rmdir /S /Q storage\framework\views
mkdir storage\framework\views

echo Cache berhasil dibersihkan.
pause
