<?php

use Faker\Generator as Faker;

$factory->define(App\Collection::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'name' => $faker->word . ' collection',
        'image_url' => 'http://placehold.it/200/f0f0f7',
        'description' => $faker->paragraph,
    ];
});
