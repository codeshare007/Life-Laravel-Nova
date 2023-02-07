<?php

use Faker\Generator as Faker;

$factory->define(App\SourcingMethod::class, function (Faker $faker) {
    return [
        'name' => $faker->word . ' sourcing method',
    ];
});
