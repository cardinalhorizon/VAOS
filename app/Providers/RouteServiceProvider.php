<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use RecursiveIteratorIterator as Iterator;
use RecursiveDirectoryIterator as DirectoryIterator;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace'  => $this->namespace,
        ], function ($router) {
            $iterator = new Iterator(new DirectoryIterator(base_path('routes/web')), Iterator::SELF_FIRST);

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    require $file->getPathname();
                }
            }
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'prefix'     => 'api',
            'middleware' => 'api',
            'namespace'  => $this->namespace,
        ], function ($router) {
            $iterator = new Iterator(new DirectoryIterator(base_path('routes/api')), Iterator::SELF_FIRST);

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    require $file->getPathname();
                }
            }
        });
    }
}
