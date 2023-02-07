<?php

use Faker\Generator as Faker;

$factory->define(App\View::class, function (Faker $faker) {
    return [
        'created_at' => $faker->dateTimeBetween($startDate = '-60 days', $endDate = 'now'),
        'viewable_type' => App\Oil::class,
        'viewable_id' => function () {
            return factory(App\Oil::class)->create()->id;
        },
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
    ];
});
