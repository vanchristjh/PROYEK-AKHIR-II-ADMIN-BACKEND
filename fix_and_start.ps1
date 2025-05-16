# This script will perform the following:
# 1. Stop any running PHP servers
# 2. Fix the redirect loop
# 3. Run the submission data migration
# 4. Start the PHP server

# Stop any running PHP servers
Write-Host "Stopping any running PHP servers..." -ForegroundColor Yellow
taskkill /f /im php.exe > $null 2>&1

# Change to the project directory
cd "D:\SEMUA TENTANG KULIAH\SEMESTER 4\PA2\IMPLEMENTASI NEW\sman1-girsip"

# Fix the redirect loop
Write-Host "Fixing redirect loop..." -ForegroundColor Yellow
php fix_redirect_loop_final.php

# Run the submission data migration
Write-Host "Migrating submission data..." -ForegroundColor Yellow
php migrate_submission_data.php

# Start the PHP server
Write-Host "Starting the PHP server..." -ForegroundColor Green
Start-Process -FilePath "php" -ArgumentList "-S", "localhost:8000", "-t", "public"

Write-Host "Server started at http://localhost:8000" -ForegroundColor Cyan
Write-Host "Press CTRL+C to stop the server" -ForegroundColor Cyan
