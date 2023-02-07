<?php

use Faker\Generator as Faker;

$factory->define(App\Favourite::class, function (Faker $faker) {
    return [
        'favouriteable_type' => App\Oil::class,
        'favouriteable_id' => function () {
            return factory(App\Oil::class)->create()->id;
        },
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
    ];
});
