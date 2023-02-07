<?php

use Faker\Generator as Faker;

$factory->define(App\Ailment::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'name' => $faker->randomElement(['Calcified Spine', 'Cancer (bladder)', 'Cancer (blood)', 'Cancer (bone)', 'Cancer (brain)', 'Cancer (breast)', 'Cancer (cervical)', 'Cancer (colon)', 'Cancer (follicular thyroid)', 'Cancer (hurthle cell thyroid)', 'Cancer (liver)', 'Cancer (lung)', 'Cancer (lymph)', 'Cancer (ovarian)', 'Cancer (prostate)', 'Cancer (throat)', 'Cancer (tongue)', 'Candida', 'Canker Sores', 'Cartilage Injury', 'Cavities', 'Cellulite', 'Chemical Imbalance', 'Chest Infection', 'Chicken Pox', 'Chiggers', 'Cholera', 'Cholesterol (high)', 'Chondromalacia Patella', 'Chronic Fatigue', 'Chronic Pain', 'Circulation (Poor)', 'Cirrhosis', 'Clogged Pores', 'Club Foot', 'Cold (common)', 'Cold Body Temperature', 'Cold Hands/Feet/ Nose', 'Cold Sores/Fever Blisters', 'Colic', 'Colitis', 'Coma', 'Concentration (poor)', 'Concussion', 'Confidence (lack of)', 'Confusion', 'Congenital Heart Disease', 'Congestion', 'Conjunctivitis (Pink Eye)', 'Connective Tissue Injury', 'Constipation', 'Convalescence', 'Convulsions', 'Corns', 'Cortisol Imbalance', 'Cough', 'Cough (whooping)', 'Cradle Cap', 'Cramps (menstrual)', 'Creutzfeldt-Jakob Disease', 'Crohn’s Disease', 'Croup', 'Crying Baby', 'Cushing’s Syndrome', 'Cuts', 'Cyst']),
        'short_description' => $faker->paragraph,
    ];
});
