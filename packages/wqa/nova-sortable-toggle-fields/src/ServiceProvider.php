<?php

namespace Wqa\NovaSortableToggleFields;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;

class ServiceProvider extends LaravelServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/nova-sortable-toggle-fields.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('nova-sortable-toggle-fields.php'),
        ], 'config');

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-sortable-toggle-fields', __DIR__.'/../dist/js/nova-sortable-toggle-fields.js');
            Nova::style('nova-sortable-toggle-fields', __DIR__.'/../dist/css/nova-sortable-toggle-fields.css');
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'nova-sortable-toggle-fields'
        );

        $this->app->bind('nova-sortable-toggle-fields', function () {
            return new NovaSortableToggleFields();
        });

        $this->registerRoutes();
    }

    private function registerRoutes()
    {
        Route::domain(config('nova.domain', null))
            ->middleware(config('nova.middleware', []))
            ->prefix('/nova-vendor/wqa/nova-sortable-toggle-fields')
            ->group(__DIR__ . '/Http/Routes/api.php');
    }
}
