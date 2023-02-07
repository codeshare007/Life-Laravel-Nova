<?php

use Faker\Generator as Faker;

$factory->define(App\Collectable::class, function (Faker $faker) {
    return [
        'collectable_type' => App\Oil::class,
        'collectable_id' => function () {
            return factory(App\Oil::class)->create()->id;
        },
        'collection_id' => function () {
            return factory(App\Collection::class)->create()->id;
        },
    ];
});
