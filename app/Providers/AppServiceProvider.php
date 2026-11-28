<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('layouts.partials.footer', function ($view) {
            $view->with(
                'footerCategories',
                Category::whereIn('nom', ['Entrepreneurs', 'Artistes', 'Sportifs', 'Diaspora', 'International', 'Partenaires'])
                    ->get()
                    ->keyBy('nom')
            );
        });

            // 5 tentatives/minute par couple email + IP (un autre compte n'est pas bloqué)
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by(Str::lower((string) $request->input('email')) . '|' . $request->ip());
        });
    
        RateLimiter::for('register', fn (Request $request) => Limit::perMinutes(10, 3)->by($request->ip()));
        RateLimiter::for('logout',   fn (Request $request) => Limit::perMinute(20)->by($request->ip()));
        RateLimiter::for('public-api', fn (Request $request) => Limit::perMinute(60)->by($request->ip()));
RateLimiter::for('pub-clic', fn (Request $request) => Limit::perMinute(30)->by($request->ip()));

    }
}
