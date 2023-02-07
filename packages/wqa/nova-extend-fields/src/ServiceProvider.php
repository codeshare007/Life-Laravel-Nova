<?php

namespace Wqa\NovaExtendFields;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;

class ServiceProvider extends LaravelServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/nova-extend-fields.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('nova-extend-fields.php'),
        ], 'config');

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-extend-fields', __DIR__.'/../dist/js/nova-extend-fields.js');
            Nova::style('nova-extend-fields', __DIR__.'/../dist/css/nova-extend-fields.css');
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'nova-extend-fields'
        );

        $this->app->bind('nova-extend-fields', function () {
            return new NovaExtendFields();
        });

        $this->registerRoutes();
    }

    private function registerRoutes()
    {
        Route::domain(config('nova.domain', null))
            ->middleware(config('nova.middleware', []))
            ->prefix('/nova-vendor/wqa/nova-extend-fields')
            ->group(__DIR__ . '/Http/Routes/api.php');
    }
}
