<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\Category;

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
    }
}