@echo off
echo Starting Laravel development server with increased timeout...
cd /d "%~dp0"
echo Current directory: %CD%
echo Checking if php_custom_config.ini exists...
if exist php_custom_config.ini (
    echo PHP config file found!
) else (
    echo PHP config file NOT found!
)
echo Running PHP with custom config...
php -c "php_custom_config.ini" -i | findstr "max_execution_time|memory_limit"
echo Starting server...
php -c "php_custom_config.ini" artisan serve --port=8000
