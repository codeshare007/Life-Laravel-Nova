<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        \App\User::observe(\App\Observers\UserObserver::class);
        \App\Oil::observe(\App\Observers\OilObserver::class);
        \App\Blend::observe(\App\Observers\BlendObserver::class);
        \App\Avatar::observe(\App\Observers\AvatarObserver::class);
    }
}
