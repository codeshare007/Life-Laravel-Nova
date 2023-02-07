<?php

namespace Wqa\PdfExtract;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/pdf-extract.php';

    protected $commands = [
        'Wqa\PdfExtract\Commands\ExtractPage',
        'Wqa\PdfExtract\Commands\ExtractArea',
    ];

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('pdf-extract.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'/Migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'pdf-extract'
        );

        $this->app->bind('pdf-extract', function () {
            return new PdfExtract();
        });

        $this->commands($this->commands);
    }
}
