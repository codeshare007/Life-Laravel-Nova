<?php

use Faker\Generator as Faker;

$factory->define(App\Blend::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'name' => $faker->randomElement(['Anti-aging', 'Cellular', 'Cleansing', 'Comforting', 'Detoxification', 'Digestion', 'Encouraging', 'Focus', 'Grounding', 'Holiday', 'Inspiring', 'Invigorating', 'Joyful', 'Massage', 'Men\'s', 'Metabolic', 'Oil Blends', 'Protective', 'Reassuring', 'Renewing', 'Repellent', 'Respiration', 'Restful', 'Skin Clearing', 'Soothing', 'Tension', 'Uplifting', 'Women\'s', 'Women\s Perfume']),
        'image_url' => 'http://placehold.it/200/f0f0f7',
        'color' => '000000',
        'emotion_1' => $faker->word,
        'emotion_2' => $faker->word,
        'emotion_3' => $faker->word,
        'fact' => $faker->paragraph,
        'safety_information_id' => function () {
            return factory(App\SafetyInformation::class)->create()->id;
        },
    ];
});
