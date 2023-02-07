<?php

namespace Tests\Unit\Models;

use App\User;
use App\Avatar;
use App\Question;
use App\Favourite;
use App\Collection;
use App\Enums\Platform;
use Tests\TestCase;
use App\Enums\Region;
use App\Enums\UserLanguage;
use App\NotificationSettings;
use Tests\Traits\TestsFields;
use Illuminate\Support\Carbon;
use App\Enums\SubscriptionType;
use Tests\Traits\RefreshAllDatabases;
use App\Services\LanguageDatabaseService;
use App\Services\Subscriptions\iOS\iOSSubscription;

class UserTest extends TestCase
{
    use RefreshAllDatabases;
    use TestsFields;

    public function test_user_can_have_avatar()
    {
        $avatar = factory(Avatar::class)->create();
        $user = factory(User::class)->create();

        $this->assertEquals(1, Avatar::count());
        $this->assertEquals($avatar->id, $user->fresh()->avatar->id);
    }

    public function test_user_can_have_notification_settings()
    {
        $user = factory(User::class)->create();

        $this->assertEquals(1, NotificationSettings::count());
        $this->assertEquals(NotificationSettings::first()->id, $user->fresh()->notificationSettings->id);
    }

    public function test_user_can_have_favourites()
    {
        $user = factory(User::class)->create();
        $favourite = factory(Favourite::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals(1, $user->favourites->count());
    }

    public function test_user_can_have_collections()
    {
        $user = factory(User::class)->create();
        $collection = factory(Collection::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals(1, $user->collections->count());
    }

    public function test_when_user_is_deleted_so_are_their_collections()
    {
        $user = factory(User::class)->create();
        $collection = factory(Collection::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals(1, $user->collections->count());

        $user->delete();

        $this->assertCount(0, Collection::all());
    }

    public function test_user_can_have_settings_as_a_json_object()
    {
        $user = factory(User::class)->create();

        $dummySettings = [
            'option' => 'value',
        ];

        $user->settings = $dummySettings;
        $user->save();

        $this->assertEquals(User::first()->settings, $dummySettings);
    }

    public function test_user_can_have_questions()
    {
        $user = factory(User::class)->create();
        $question = factory(Question::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals(1, $user->questions->count());
    }

    public function test_can_update_subscription_details_using_subscription_model()
    {
        $user = factory(User::class)->create([
            'subscription_type' => null,
            'subscription_expires_at' => null,
            'platform' => null,
        ]);
        $subscription = (new iOSSubscription())->applyBypass();

        $user->updateSubscription($subscription);

        $this->assertEquals($subscription->type, $user->subscription_type);
        $this->assertEquals($subscription->expiration->startOfDay(), $user->subscription_expires_at->startOfDay());
        $this->assertEquals($subscription->platform, $user->platform);
    }

    public function test_can_get_language_using_db_connection_details()
    {
        $enUser = factory(User::class)->create();

        $this->assertEquals(UserLanguage::English(), $enUser->language);

        $dbService = new LanguageDatabaseService();
        $dbService->setLanguage(UserLanguage::Spanish());
        $esUser = factory(User::class)->create();
        $dbService->reset();

        $this->assertEquals(UserLanguage::Spanish(), $esUser->language);
    }

    public function test_can_get_firebase_id()
    {
        $user = factory(User::class)->create([
            'id' => 100,
        ]);

        $this->assertEquals('en_100', $user->firebase_id);
    }

    public function test_can_get_firebase_id_for_es_user()
    {
        $dbService = new LanguageDatabaseService();
        $dbService->setLanguage(UserLanguage::Spanish());
        $esUser = factory(User::class)->create([
            'id' => 100,
        ]);
        $dbService->reset();

        $this->assertEquals('es_100', $esUser->firebase_id);
    }

    public function test_user_region_is_set_to_us_by_default()
    {
        $user = factory(User::class)->create();

        $this->assertEquals(Region::US, $user->region()->value);
    }

    public function test_can_get_user_region()
    {
        $user = factory(User::class)->create([
            'region_id' => Region::Malaysia,
        ]);

        $this->assertEquals(Region::Malaysia, $user->region()->value);
    }

    public function test_can_get_klaviyo_profile()
    {
        $user = factory(User::class)->create([
            'name' => 'David Charleston',
            'created_at' => Carbon::create(2022, 1, 14, 0, 0, 0),
            'subscription_type' => SubscriptionType::TheEssentialLifeMembership12Month,
            'subscription_expires_at' => Carbon::create(2022, 2, 1, 0, 0, 0),
            'region_id' => Region::Australia,
            'last_logged_in_at' => Carbon::create(2022, 1, 14, 0, 0, 0),
            'last_used_app_at' => Carbon::create(2022, 1, 17, 0, 0, 0),
            'app_version' => '4.3.2',
            'app_build_number' => '187',
            'system_version' => '15.3.1',
            'device_name' => 'iPhone14,5',
            'platform' => Platform::iOS,
        ]);

        $this->assertEquals([
            'region' => Region::Australia()->key,
            'name' => 'David Charleston',
            'essential_life_account_created_at' => '2022-01-14 00:00:00',
            'subscription_type' => SubscriptionType::TheEssentialLifeMembership12Month()->key,
            'subscription_expires_at' => '2022-02-01 00:00:00',
            'last_logged_in_at' => '2022-01-14 00:00:00',
            'last_used_app_at' => '2022-01-17 00:00:00',
            'app_version' => '4.3.2',
            'app_build_number' => '187',
            'system_version' => '15.3.1',
            'device_name' => 'iPhone14,5',
            'platform' => Platform::iOS()->key,
            'language' => UserLanguage::English()->key,
        ], $user->klaviyoProfile());
    }
}
