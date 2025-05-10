<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load custom PHP configuration settings
        if (file_exists(config_path('php_config.php'))) {
            require config_path('php_config.php');
        }
    }
}
