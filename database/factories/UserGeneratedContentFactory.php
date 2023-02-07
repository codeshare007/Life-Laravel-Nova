<?php

use App\Enums\UserGeneratedContentStatus;
use Faker\Generator as Faker;

$factory->define(App\UserGeneratedContent::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create();
        },
        'name' => $faker->words(2, true),
        'type' => $faker->randomElement(['Remedy', 'Recipe']),
        'status' => UserGeneratedContentStatus::InReview,
        'content' => [
            'ailment' => [1,2,3],
            'bodySystem' => [1],
            'ingredients' => [],
        ],
        'is_public' => true,
        'uuid' => $faker->uuid,
    ];
});
