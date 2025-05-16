# PowerShell script to start Laravel development server with increased timeouts
Write-Host "Starting Laravel development server with increased timeouts..." -ForegroundColor Cyan

# Ensure Laravel cache is cleared to avoid redirect issues
Write-Host "Clearing Laravel caches to avoid redirect issues..." -ForegroundColor Yellow
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear

# Setting PHP configuration directly via command line options
Write-Host "Starting server (HTTP mode)..." -ForegroundColor Green
php -d max_execution_time=1800 `
    -d memory_limit=512M `
    -d upload_max_filesize=20M `
    -d post_max_size=21M `
    -d default_socket_timeout=1800 `
    -d session.cookie_secure=0 `
    -d display_errors=On `
    artisan serve --port=8090 --host=localhost

# Check if server failed to start
if ($LASTEXITCODE -ne 0) {
    Write-Host "Server failed to start. See error above." -ForegroundColor Red
    Read-Host -Prompt "Press Enter to continue"
}
