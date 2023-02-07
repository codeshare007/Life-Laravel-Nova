<?php

use App\Enums\Region;
use App\Enums\RegionableModels;
use Faker\Generator as Faker;

$factory->define(App\RegionalName::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
