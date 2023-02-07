<?php

use Faker\Generator as Faker;

$factory->define(App\BodySystem::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'name' => $faker->randomElement(['Addictions', 'Allergies', 'Athletes', 'Autoimmune', 'Blood Sugar', 'Brain', 'Candida', 'Cardiovascular', 'Cellular Health', 'Children', 'Detoxification', 'Digestive & Intestinal', 'Eating Disorders', 'Endocrine', 'Energy & Vitality', 'First Aid', 'Focus & Concentration', 'Immune & Lymphatic', 'Integumentary (hair, skin, nails)', 'Intimacy', 'Limbic', 'Men’s Healt', 'Mood & Behavior', 'Muscular Nervous System', 'Oral Health', 'Pain & Inflammation', 'Parasites', 'Pregnancy, Labor & Nursing', 'Respiratory', 'Skeletal', 'Sleep', 'Stress', 'Urinary', 'Weight', 'Women’s Health']),
        'image_url' => 'http://placehold.it/200/f0f0f7',
        'color' => '000000',
        'short_description' => $faker->paragraph,
        'usage_tip' => $faker->paragraph,
    ];
});
