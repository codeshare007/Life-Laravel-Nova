<?php

namespace App\Providers;

use App\Events\UserUsedAppEvent;
use App\Events\UserLoggedInEvent;
use App\Events\ElementCreatedEvent;
use App\Events\UserSwitchedLanguage;
use App\Events\QuestionApprovedEvent;
use App\Events\QuestionRejectedEvent;
use App\Listeners\UpdateUserLastUsedAppAt;
use App\Listeners\SendApprovedNotification;
use App\Listeners\SendRejectedNotification;
use App\Listeners\UpdateUserLastLoggedInAt;
use App\Providers\SyncUserProfileToKlaviyo;
use App\Events\ApprovedUserGeneratedContent;
use App\Events\RejectedUserGeneratedContent;
use App\Listeners\SendQuestionApprovedNotification;
use App\Listeners\SendQuestionRejectedNotification;
use App\Providers\PushSwitchedLanguageEventToKlaviyo;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserLoggedInEvent::class => [
            UpdateUserLastLoggedInAt::class,
            UpdateUserLastUsedAppAt::class,
            SyncUserProfileToKlaviyo::class,
            PushLoggedInEventToKlaviyo::class,
        ],
        UserUsedAppEvent::class => [
            UpdateUserLastUsedAppAt::class,
            SyncUserProfileToKlaviyo::class,
            PushUsedAppEventToKlaviyo::class,
        ],
        UserSwitchedLanguage::class => [
            SyncUserProfileToKlaviyo::class,
            PushSwitchedLanguageEventToKlaviyo::class,
        ],
        ApprovedUserGeneratedContent::class => [
            SendApprovedNotification::class,
        ],
        RejectedUserGeneratedContent::class => [
            SendRejectedNotification::class,
        ],
        ElementCreatedEvent::class => [
            // PropagateElementToLanguageDatabases::class,
        ],
        QuestionApprovedEvent::class => [
            // SendQuestionApprovedNotification::class,
        ],
        QuestionRejectedEvent::class => [
            SendQuestionRejectedNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
