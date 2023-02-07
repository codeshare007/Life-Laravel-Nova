<?php

namespace Wqa\NovaSortableTableResource;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Laravel\Nova\Events\NovaServiceProviderRegistered;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/nova-sortable-table-resource.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('nova-sortable-table-resource.php'),
        ], 'config');

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-sortable-table-resource', __DIR__.'/../dist/js/sortable-table-resource.js');
            Nova::style('nova-sortable-table-resource', __DIR__.'/../dist/css/sortable-table-resource.css');
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'nova-sortable-table-resource'
        );

        $this->app->bind('nova-sortable-table-resource', function () {
            return new NovaSortableTableResource();
        });

        $this->registerRoutes();
    }

    /**
     * Registers field routes
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::domain(config('nova.domain', null))
            ->middleware(config('nova.middleware', []))
            ->prefix('/nova-vendor/wqa/nova-sortable-table-resource')
            ->group(__DIR__ . '/Http/Routes/api.php');
    }
}
