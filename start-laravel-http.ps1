# PowerShell script to start Laravel development server with HTTPS using mkcert certificates
Write-Host "Starting Laravel development server with HTTPS..." -ForegroundColor Cyan

# Use the artisan serve command
php artisan serve --port=8090

# Check if server failed to start
if ($LASTEXITCODE -ne 0) {
    Write-Host "Server failed to start. See error above." -ForegroundColor Red
    Read-Host -Prompt "Press Enter to continue"
}
