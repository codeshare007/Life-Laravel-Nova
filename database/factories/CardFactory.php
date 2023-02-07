<?php

use App\Enums\Region;
use Faker\Generator as Faker;

$factory->define(App\Card::class, function (Faker $faker) {
    return [
        'is_active' => true,
        'title' => $faker->words(2, true),
        'subtitle' => $faker->words(2, true),
        'description' => $faker->words(10, true),
        'regions' => [Region::US],
    ];
});
