# PowerShell script to start Laravel development server with increased timeout
Write-Host "Starting Laravel development server with increased timeout..." -ForegroundColor Cyan
$scriptPath = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $scriptPath

Write-Host "Current directory: $scriptPath" -ForegroundColor Yellow
Write-Host "Checking if php_custom_config.ini exists..." -ForegroundColor Yellow
if (Test-Path "php_custom_config.ini") {
    Write-Host "PHP config file found!" -ForegroundColor Green
} else {
    Write-Host "PHP config file NOT found!" -ForegroundColor Red
}

Write-Host "Running PHP with custom config..." -ForegroundColor Yellow
php -c "php_custom_config.ini" -i | Select-String "max_execution_time|memory_limit"

Write-Host "Starting server..." -ForegroundColor Green
php -c "php_custom_config.ini" artisan serve --port=8000
