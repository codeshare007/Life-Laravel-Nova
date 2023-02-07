<?php

namespace App\Providers;

use Laravel\Nova\Nova;
use App\Nova\Metrics\UsersPerDay;
use Wqa\GlobalStatus\GlobalStatus;
use Wqa\SeedDownload\SeedDownload;
use App\Nova\Metrics\UsersPerMonth;
use Illuminate\Support\Facades\Gate;
use App\Nova\Metrics\TotalUsersCount;
use App\Nova\Metrics\LegacyUsersCount;
use App\Nova\Metrics\ActiveTrialUsersCount;
use App\Nova\Metrics\InactiveTrialUsersCount;
use App\Nova\Metrics\UsersPerSubscriptionType;
use Laravel\Nova\NovaApplicationServiceProvider;
use Christophrumpel\NovaNotifications\NovaNotifications;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
    * Configure the Nova authorization services.
    *
    * @return void
    */
    protected function authorization()
    {
        $this->gate();

        Nova::auth(function ($request) {
            return Gate::check('viewNova', [$request->user()]);
        });
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return $user->is_admin;
        });
    }

    /**
     * Get the cards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            (new LegacyUsersCount)->width('1/4'),
            (new ActiveTrialUsersCount)->width('1/4'),
            (new InactiveTrialUsersCount)->width('1/4'),
            (new TotalUsersCount)->width('1/4'),
            (new UsersPerSubscriptionType)->width('1/3'),
            (new UsersPerDay)->width('1/3'),
            (new UsersPerMonth)->width('1/3'),
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new GlobalStatus,
            new SeedDownload,
            // new NovaNotifications,
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
