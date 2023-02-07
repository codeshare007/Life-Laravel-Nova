<?php

use Faker\Generator as Faker;

$factory->define(App\Solution::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'name' => $faker->word,
        'description' => $faker->paragraph,
        'solutionable_type' => App\Oil::class,
        'solutionable_id' => function () {
            return factory(App\Oil::class)->create()->id;
        },
    ];
});
