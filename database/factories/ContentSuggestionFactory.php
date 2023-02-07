<?php

use Faker\Generator as Faker;
use App\Enums\ContentSuggestionMode;
use App\Enums\ContentSuggestionType;

$factory->define(App\ContentSuggestion::class, function (Faker $faker) {
    return [
        'name' => $faker->words(3, true),
        'type' => ContentSuggestionType::getRandomValue(),
        'mode' => ContentSuggestionMode::getRandomValue(),
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'association_id' => 0,
        'association_type' => null,
        'content' => $faker->paragraph(),
    ];
});
