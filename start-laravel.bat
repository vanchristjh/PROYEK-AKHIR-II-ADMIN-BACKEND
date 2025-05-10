@echo off
echo Starting Laravel development server with increased timeouts...
cd /d "%~dp0"

REM Setting PHP configuration directly via command line options
php -d max_execution_time=1800 ^
    -d memory_limit=512M ^
    -d upload_max_filesize=20M ^
    -d post_max_size=21M ^
    -d default_socket_timeout=1800 ^
    -d display_errors=On ^
    artisan serve --port=8090

REM If server fails to start, pause to see error
if %ERRORLEVEL% NEQ 0 (
    echo Server failed to start. See error above.
    pause
)
