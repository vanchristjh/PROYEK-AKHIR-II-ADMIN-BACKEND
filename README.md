<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# SMA Admin Dashboard

## About This Application

This is a comprehensive school management system for SMA Negeri 1 Girsang Sipangan Bolon. The system manages students, teachers, classes, schedules, attendance, announcements, and academic calendars.

## Features

- **User Management**
  - Student accounts management
  - Teacher accounts management
  - Role-based permissions

- **Academic Management**
  - Class management
  - Subject management
  - Schedule management
  - Attendance tracking
  - Academic calendar

- **Communication**
  - Announcements
  - Notifications

- **Mobile Integration**
  - REST API for mobile app
  - Authentication via Sanctum
  - Schedule, announcement, and calendar access

## Requirements

- PHP >= 8.2
- Laravel 12
- MySQL or PostgreSQL
- Composer
- Node.js and NPM

## Installation

1. Clone the repository
   ```
   git clone https://github.com/yourusername/sma-admin-dashboard.git
   ```

2. Install dependencies
   ```
   composer install
   npm install
   ```

3. Copy the .env file and set up your database
   ```
   cp .env.example .env
   php artisan key:generate
   ```

4. Run migrations and seeders
   ```
   php artisan migrate --seed
   ```

5. Link storage for file uploads
   ```
   php artisan storage:link
   ```

6. Compile assets
   ```
   npm run build
   ```

7. Start the development server
   ```
   php artisan serve
   ```

## API Documentation

The application includes a comprehensive API for mobile integration. See the API_INTEGRATION.md file for details on endpoints and integration with mobile applications.

## License

This application is proprietary software of SMA Negeri 1 Girsang Sipangan Bolon.
