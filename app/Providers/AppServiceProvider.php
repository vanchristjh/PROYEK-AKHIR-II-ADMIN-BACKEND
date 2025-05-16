<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use App\View\Components\ActivityItem;
use App\Models\Announcement;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Make sure we're not overriding the default Filesystem implementation
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Register the PDF Facade
        $this->app->bind('PDF', function ($app) {
            return new \Barryvdh\DomPDF\PDF($app);
        });

        // Add this line if you're using Tailwind CSS
        Paginator::useBootstrapFive(); // or Paginator::useBootstrap(); for Bootstrap 4
        
        // Or if you want to use Tailwind CSS:
        // Paginator::defaultView('pagination::tailwind');
        // Paginator::defaultSimpleView('pagination::simple-tailwind');
        
        // Load custom PHP configuration settings
        if (file_exists(config_path('php_config.php'))) {
            require config_path('php_config.php');
        }
        
        // Register custom blade components
        Blade::component('activity-item', ActivityItem::class);

        // Use Bootstrap for pagination
        Paginator::useBootstrap();

        // Share important announcements count with all views
        view()->composer('*', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                $role = $user->role->name ?? 'unknown';
                
                $audience = 'all';
                if ($role === 'admin') {
                    $audience = ['all', 'administrators'];
                } elseif ($role === 'guru') {
                    $audience = ['all', 'teachers'];
                } elseif ($role === 'siswa') {
                    $audience = ['all', 'students'];
                }
                
                $unreadImportantAnnouncements = Announcement::where('is_important', true)
                    ->published()
                    ->notExpired()
                    ->forAudience($audience)
                    ->where('publish_date', '>=', Carbon::now()->subDays(7))
                    ->count();
                
                $view->with('unreadImportantAnnouncements', $unreadImportantAnnouncements);
            }
        });
    }
}
