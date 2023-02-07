<?php

namespace Wqa\NovaExtendResources;

use Laravel\Nova\NovaServiceProvider;
use Illuminate\Support\Facades\Route;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;

class ServiceProvider extends NovaServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/nova-extend-resources.php';

    public function boot()
    {
        parent::boot();

        //$this->loadViewsFrom(resource_path('views'), 'nova');

        $this->publishes([
            self::CONFIG_PATH => config_path('nova-extend-resources.php'),
        ], 'config');

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-extend-resources', __DIR__.'/../dist/js/nova-extend-resources.js');
            Nova::style('nova-extend-resources', __DIR__.'/../dist/css/nova-extend-resources.css');
        });
    }

    public function register()
    {
        parent::register();

        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'nova-extend-resources'
        );

        $this->app->bind('nova-extend-resources', function () {
            return new NovaExtendResources();
        });

        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        parent::registerRoutes();

        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/Http/Routes/api.php');
        });
    }

    /**
     * Get the Nova route group configuration array.
     *
     * @return array
     */
    protected function routeConfiguration()
    {
        return [
            'namespace' => __NAMESPACE__.'\Http\Controllers\Nova',
            'domain' => config('nova.domain', null),
            'as' => 'nova.api.',
            'prefix' => 'nova-api',
            'middleware' => 'nova',
        ];
    }
}
