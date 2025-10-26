<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Vite;
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
        Vite::prefetch(concurrency: 3);

        // Rate limiter pre kontaktný formulár
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perHour(10)->by($request->ip())
                ->response(function () {
                    return back()->withErrors([
                        'message' => 'Príliš veľa pokusov. Skúste to prosím neskôr.'
                    ]);
                });
        });
    }
}
