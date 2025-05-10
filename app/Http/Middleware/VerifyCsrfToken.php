<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // If you need to exclude any routes from CSRF protection, add them here
        // For example: 'api/*'
    ];
      /**
     * Increase CSRF token timeout for a better user experience
     * Default is typically 120 minutes (2 hours)
     * 
     * @var int
     */
    protected $except_time = 240; // 4 hours
     */
    protected $tokensLifetime = 240; // 4 hours in minutes
}
