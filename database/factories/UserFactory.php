<?php

use Faker\Generator as Faker;
use App\Enums\SubscriptionType;

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'bio' => 'My bio here',
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'is_admin' => false,
        'settings' => null,
        'subscription_type' => SubscriptionType::TheEssentialLifeMembership12Month(),
        'subscription_expires_at' => now()->addYear(),
        'avatar_url' => 'https://example.com/image.jpg',
        'region_id' => null,
        'bypass_subscription_receipt_validation' => false,
    ];
});
