<?php

use App\Enums\CommentReport\ActionTaken;
use App\Enums\CommentReport\Status;
use Faker\Generator as Faker;

$factory->define(App\CommentReport::class, function (Faker $faker) {
    return [
        'reporter_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'commenter_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'reason' => $faker->paragraph(),
        'comment' => $faker->paragraph(),
        'element_uuid' => $faker->uuid,
        'status' => Status::Open,
        'action_taken' => ActionTaken::None,
        'firebase_document' => '/path/to/document',
    ];
});
