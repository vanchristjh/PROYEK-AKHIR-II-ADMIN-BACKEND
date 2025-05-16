<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\ActivityLog;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function ($event) {
            try {
                ActivityLog::log('login', 'User logged in successfully', ['email' => $event->user->email]);
            } catch (\Exception $e) {
                // Silent fail if the activity log table doesn't exist yet
            }
        });

        Event::listen(Logout::class, function ($event) {
            try {
                ActivityLog::log('logout', 'User logged out', ['email' => $event->user->email]);
            } catch (\Exception $e) {
                // Silent fail if the activity log table doesn't exist yet
            }
        });
    }
}