<?php

use Faker\Generator as Faker;
use App\Enums\NotificationFrequency;

$factory->define(App\NotificationSettings::class, function (Faker $faker) {
    return [
        'enabled' => $faker->boolean,
        'notify_for_favourites' => $faker->boolean,
        'frequency' => NotificationFrequency::getRandomValue(),
    ];
});
