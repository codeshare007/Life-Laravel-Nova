<?php

namespace App\Providers;

use App\ContentSuggestion;
use App\Like;
use App\Favourite;
use App\Collection;
use App\Collectable;
use App\Policies\ContentSuggestionPolicy;
use App\Policies\LikePolicy;
use App\Policies\UserGeneratedContentPolicy;
use App\UserGeneratedContent;
use Laravel\Passport\Passport;
use App\Policies\FavouritePolicy;
use App\Policies\CollectionPolicy;
use App\Policies\CollectablePolicy;
use App\Policies\NotificationPolicy;
use Illuminate\Notifications\DatabaseNotification as Notification;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Favourite::class => FavouritePolicy::class,
        Collection::class => CollectionPolicy::class,
        Collectable::class => CollectablePolicy::class,
        Like::class => LikePolicy::class,
        Notification::class => NotificationPolicy::class,
        UserGeneratedContent::class => UserGeneratedContentPolicy::class,
        ContentSuggestion::class => ContentSuggestionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
