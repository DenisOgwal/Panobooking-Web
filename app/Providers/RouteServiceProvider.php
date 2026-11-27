<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')->prefix('api/v1')->name('api.')->group(base_path('routes/api.php'));
            Route::middleware('web')->group(base_path('routes/web.php'));
        });
    }

    protected function configureRateLimiting(): void
    {
        // Everything in the API, per customer token or per IP.
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(120)
            ->by($request->bearerToken() ? 't:' . sha1($request->bearerToken()) : 'ip:' . $request->ip()));

        // Sign-in, sign-up, codes: per email and IP, so guessing codes or passwords is slow.
        RateLimiter::for('api-auth', fn (Request $request) => Limit::perMinute(6)
            ->by(strtolower((string) $request->input('email')) . '|' . $request->ip()));

        // Public forms (contact, comments, testimonials, listings).
        RateLimiter::for('api-forms', fn (Request $request) => Limit::perMinute(10)->by('ip:' . $request->ip()));
    }
}
