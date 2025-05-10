# PowerShell script to start Laravel development server with increased timeouts
Write-Host "Starting Laravel development server with increased timeouts..." -ForegroundColor Cyan

# Setting PHP configuration directly via command line options
php -d max_execution_time=1800 `
    -d memory_limit=512M `
    -d upload_max_filesize=20M `
    -d post_max_size=21M `
    -d default_socket_timeout=1800 `
    -d display_errors=On `
    artisan serve --port=8090

# Check if server failed to start
if ($LASTEXITCODE -ne 0) {
    Write-Host "Server failed to start. See error above." -ForegroundColor Red
    Read-Host -Prompt "Press Enter to continue"
}
