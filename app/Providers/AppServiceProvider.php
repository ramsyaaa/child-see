<?php

namespace App\Providers;

use App\Models\SiteSettings;
use Illuminate\Support\Facades\View;
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
        View::composer('home.*', function ($view) {
            try {
                $site = SiteSettings::all_as_array();
            } catch (\Exception $e) {
                $site = [];
            }
            $view->with('site', $site);
        });
    }
}
