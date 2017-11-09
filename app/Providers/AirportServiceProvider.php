<?php

namespace App\Providers;

use App\Services\AirportService;
use Illuminate\Support\ServiceProvider;

class AirportServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\AirportService', function ($app) {
            return new AirportService();
        });
    }
}
