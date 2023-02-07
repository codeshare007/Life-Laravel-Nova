<?php

use Faker\Generator as Faker;

$factory->define(App\Avatar::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'image_url' => 'http://placehold.it/200x200/f0f0f7',
    ];
});
