<?php

use App\Enums\Question\Status;
use App\User;
use Faker\Generator as Faker;

$factory->define(App\Question::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'category' => $faker->word,
        'title' => $faker->sentence(5),
        'description' => $faker->paragraph,
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'is_featured' => false,
        'firebase_document' => null,
        'status' => Status::InReview(),
    ];
});
