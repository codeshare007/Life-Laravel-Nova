<?php

use App\Enums\TagType;
use Faker\Generator as Faker;

$factory->define(App\Tag::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'name' => $faker->randomElement(['Anaphrodisiac', 'Analgesic', 'Anti-allergenic', 'Antiarthritic', 'Antibacterial', 'Anticarcinogenic', 'Anti-carcinoma', 'Anticatarrhal', 'Anticoagulant', 'Anticonvulsant', 'Antidepressant', 'Antiemetic', 'Antifungal', 'Antihemorrhagic', 'Antihistamine', 'Anti-infectious', 'Anti-inflammatory', 'Antimicrobial', 'Antimutagenic ', 'Antioxidant', 'Anti-parasitic', 'Anti-rheumatic', 'Antiseptic', 'Antispasmodic', 'Antitoxic', 'Anti-tumoral', 'Antiviral', 'Aphrodisiac', 'Astringent', 'Calming', 'Cardiotonic', 'Carminative', 'Cleanser', 'Cytophylactic', 'Decongestant', 'Deodorant', 'Detoxifier', 'Digestive stimulant', 'Disinfectant', 'Diuretic', 'Emmenagogue', 'Energizing', 'Expectorant', 'Galactagogue', 'Grounding', 'Hypertensive', 'Hypotensive', 'Immunostimulant', 'Insect repellant', 'Insecticidal', 'Invigorating', 'Laxative', 'Mucolytic', 'Nervine ', 'Neuroprotective', 'Neurotonic', 'Purifier', 'Refreshing', 'Regenerative', 'Relaxing', 'Restorative', 'Revitalizer', 'Rubefacient', 'Sedative', 'Steroidal', 'Stimulant', 'Stomachic ', 'Tonic', 'Uplifting', 'Vasoconstrictor', 'Vasodilator', 'Vermicide', 'Vermifuge', 'Warming']),
        'type' => TagType::getRandomValue(),
    ];
});
