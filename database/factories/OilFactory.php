<?php

use Faker\Generator as Faker;

$factory->define(App\Oil::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'name' => $faker->randomElement(['Arborvitae', 'Basil', 'Bergamot', 'Birch', 'Black Pepper', 'Blue Tansy', 'Cardamom', 'Cassia', 'Cedarwood', 'Cilantro', 'Cinnamon', 'Clary sage', 'Clove', 'Copaiba', 'Coriander', 'Cypress', 'Dill', 'Douglas fir', 'Eucalyptus', 'Fennel', 'Frankincense', 'Geranium', 'Ginger', 'Grapefruit', 'Helichrysum', 'Jasmine', 'Juniper berry', 'Lavender', 'Lemon', 'Lemongrass', 'Lime', 'Litsea', 'Manuka', 'Marjoram', 'Melaleuca', 'Melissa', 'Myrrh', 'Neroli', 'Oregano', 'Patchouli', 'Peppermint', 'Petitgrain', 'Ravensara', 'Roman chamomile', 'Rose', 'Rosemary', 'Sandalwood', 'Siberianfir', 'Spearmint', 'Spikenard', 'Tangerine', 'Thyme', 'Vetiver', 'White fir', 'Wild orange', 'Wintergreen', 'Yarrow', 'Ylangylang']),
        'image_url' => 'http://placehold.it/200/f0f0f7',
        'color' => '000000',
        'latin_name' => $faker->words(2, true),
        'emotion_1' => $faker->word,
        'emotion_2' => $faker->word,
        'emotion_3' => $faker->word,
        'fact' => $faker->paragraph,
        'research' => $faker->paragraph,
        'safety_information_id' => function () {
            return factory(App\SafetyInformation::class)->create()->id;
        },
    ];
});
