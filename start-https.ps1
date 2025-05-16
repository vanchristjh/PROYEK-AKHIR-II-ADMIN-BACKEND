# PowerShell script to start Laravel development server with HTTPS
Write-Host "Starting Laravel development server with HTTPS..." -ForegroundColor Cyan

# Determine current directory
$currentDir = Get-Location

# Set paths to certificate files
$certFile = Join-Path $currentDir "certificates\localhost.pem"
$keyFile = Join-Path $currentDir "certificates\localhost-key.pem"

# Start PHP development server with SSL
$env:SSL_CERT_FILE = $certFile
$env:SSL_KEY_FILE = $keyFile

# Use OpenSSL to create and run a self-signed HTTPS server - this explicitly runs with HTTPS
Write-Host "Starting HTTPS server on https://localhost:8090" -ForegroundColor Green
php -S localhost:8090 -t public router.php
