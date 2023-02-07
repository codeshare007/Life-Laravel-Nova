<?php

use Faker\Generator as Faker;

$factory->define(App\Remedy::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'name' => $faker->word . ' remedy',
        'image_url' => 'http://placehold.it/200/f0f0f7',
        'color' => '000000',
        'short_description' => $faker->paragraph,
        'body' => $faker->paragraph,
        'user_id' => 0,
    ];
});
