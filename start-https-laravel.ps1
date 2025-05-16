Set-ExecutionPolicy -ExecutionPolicy Bypass -Scope Process

# Get absolute paths for the certificate files
$currentDir = Split-Path -Parent $MyInvocation.MyCommand.Definition
$certFile = Join-Path $currentDir "certificates\localhost.pem"
$keyFile = Join-Path $currentDir "certificates\localhost-key.pem"

# Check if certificates exist
if (-not (Test-Path $certFile) -or -not (Test-Path $keyFile)) {
    Write-Host "Certificate files not found at $certFile and $keyFile" -ForegroundColor Red
    Write-Host "Please make sure you have run 'mkcert -install' and created certificates for localhost" -ForegroundColor Red
    exit 1
}

# Inform the user about the approach
Write-Host "This script will setup your Laravel application to use HTTPS." -ForegroundColor Green
Write-Host "Step 1: Checking if Laravel is installed..." -ForegroundColor Cyan

# Check if Laravel is installed
if (-not (Test-Path "artisan")) {
    Write-Host "Laravel not detected in the current directory!" -ForegroundColor Red
    exit 1
}

Write-Host "Laravel found!" -ForegroundColor Green

# Step 2: Update .env file to use HTTPS
Write-Host "Step 2: Updating .env file to use HTTPS..." -ForegroundColor Cyan
$envContent = Get-Content ".env" -Raw
if ($envContent -match "APP_URL=http://") {
    $envContent = $envContent -replace "APP_URL=http://", "APP_URL=https://"
    $envContent | Set-Content ".env"
    Write-Host "Updated APP_URL to HTTPS in .env file" -ForegroundColor Green
}

Write-Host "Step 3: Starting Laravel and creating HTTPS tunnel..." -ForegroundColor Cyan
Write-Host "Laravel will be accessible at https://localhost:8090" -ForegroundColor Green
Write-Host "Press Ctrl+C to stop the server" -ForegroundColor Yellow

# Start the Laravel development server
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$currentDir'; php artisan serve --port 8000"

# Wait a moment for the Laravel server to start
Start-Sleep -Seconds 3

# Help text about accessing the site
Write-Host "Laravel is now running and available at https://localhost:8090" -ForegroundColor Green

# Keep the script running
Read-Host -Prompt "Press Enter to stop the server"

# Cleanup when the user presses Enter
Stop-Process -Name "php" -ErrorAction SilentlyContinue
Write-Host "Server stopped" -ForegroundColor Red
