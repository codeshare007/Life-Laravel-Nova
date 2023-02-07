<?php

use Faker\Generator as Faker;

$factory->define(App\Supplement::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'name' => $faker->randomElement(['Arborvitae', 'Basil', 'Bergamot', 'Birch', 'Black Pepper', 'Blue Tansy', 'Cardamom', 'Cassia', 'Cedarwood', 'Cilantro', 'Cinnamon', 'Clary sage', 'Clove', 'Copaiba', 'Coriander', 'Cypress', 'Dill', 'Douglas fir', 'Eucalyptus', 'Fennel', 'Frankincense', 'Geranium', 'Ginger', 'Grapefruit', 'Helichrysum', 'Jasmine', 'Juniper berry', 'Lavender', 'Lemon', 'Lemongrass', 'Lime', 'Litsea', 'Manuka', 'Marjoram', 'Melaleuca', 'Melissa', 'Myrrh', 'Neroli', 'Oregano', 'Patchouli', 'Peppermint', 'Petitgrain', 'Ravensara', 'Roman chamomile', 'Rose', 'Rosemary', 'Sandalwood', 'Siberianfir', 'Spearmint', 'Spikenard', 'Tangerine', 'Thyme', 'Vetiver', 'White fir', 'Wild orange', 'Wintergreen', 'Yarrow', 'Ylangylang']),
        'image_url' => 'http://placehold.it/200/f0f0f7',
        'fact' => $faker->sentence(2),
        'is_featured' => $faker->boolean,
        'color' => $faker->hexColor,
        'research' => $faker->paragraph(3),
    ];
});
