<?php

use Faker\Generator as Faker;

$factory->define(App\SafetyInformation::class, function (Faker $faker) {
    return [
        'description' => $faker->paragraph,
    ];
});
