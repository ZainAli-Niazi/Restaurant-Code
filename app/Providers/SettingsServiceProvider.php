<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share settings with all views
        View::composer('*', function ($view) {
            $restaurantSettings = Setting::getGroup('restaurant');
            $taxSettings = Setting::getGroup('tax');
            
            $view->with('restaurantSettings', $restaurantSettings)
                 ->with('taxSettings', $taxSettings);
        });
    }
}