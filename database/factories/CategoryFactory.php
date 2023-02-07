<?php

use App\Enums\CategoryType;
use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'name' => $faker->randomElement(['Baby', 'Cleaning', 'Bathroom', 'Kitchen', 'Laundry', 'Cooking', 'Beverages', 'Breads', 'Breakfast', 'Desserts', 'Dressings & Marinades', 'Fermented Foods', 'Main Dishes', 'Salads', 'Sauces & Seasonings', 'Side Dishes', 'Smoothies', 'Snacks', 'Soups', 'DIY & Gift-Giving', 'First Aid', 'Fitness', 'Garden', 'Guys', 'Holiday', 'Intimacy', 'On the Go', 'Outdoors', 'Personal Care', 'Pets', 'Pregnancy']),
        'image_url' => 'http://placehold.it/200/f0f0f7',
        'color' => '000000',
        'type' => CategoryType::getRandomValue(),
        'short_description' => $faker->paragraph,
    ];
});
