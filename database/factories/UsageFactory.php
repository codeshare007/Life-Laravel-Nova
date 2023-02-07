<?php

use Faker\Generator as Faker;

$factory->define(App\Usage::class, function (Faker $faker) {
    return [
        'name' => null,
        'description' => $faker->paragraph,
        'useable_type' => App\Oil::class,
        'useable_id' => function () {
            return factory(App\Oil::class)->create()->id;
        },
    ];
});
