<?php

namespace App\Console\Commands;

use App\Ailment;
use App\AilmentSolution;
use App\Blend;
use App\BodySystem;
use App\Card;
use App\Category;
use App\Oil;
use App\Recipe;
use App\RecipeIngredient;
use App\Remedy;
use App\RemedyIngredient;
use App\SafetyInformation;
use App\Solution;
use App\SourcingMethod;
use App\Supplement;
use App\SupplementIngredient;
use App\Tag;
use App\Usage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use Tests\Feature\Api\v2_1\SymptomTest;

class AddTranslationData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:add-translations {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add translation data for single models. You can select `all` or individual entities: ' .
        'oils, body-systems, blends, supplements, ailments, symptoms, recipes, recipe-ingredients, recipe-categories, ' .
        'supplement-ingredients, sourcing-methods, property-constituents, remedies, remedy-ingredients, ' .
        'safety-information, ailment-solutions, solutions, usages';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        throw new \Exception('If you need to use this command, then it needs to be rewitten to use the LanguageDatabaseService to handle language DB switching.');

        // single oils
        $mappings = [
            [
                'type' => 'oils',
                'class' => Oil::class,
                'file' => app_path('translations/single-oils.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'fact' => 'fact Spanish',
                        'emotion_1' => 'descriptor 1 spanish',
                        'emotion_2' => 'descriptor 2 spanish',
                        'emotion_3' => 'descriptor 3 spanish',
                        'emotion_from' => 'Spanish From',
                        'emotion_to' => 'Spanish To',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'fact' => 'fact Portuguese',
                        'emotion_1' => 'descriptor 1 Portuguese',
                        'emotion_2' => 'Descriptor 2 Portuguese',
                        'emotion_3' => 'Descriptor 3 Portuguese',
                        'emotion_from' => 'Spanish From',
                        'emotion_to' => 'Spanish To',
                    ],
                ],
            ],
            [
                'type' => 'body-systems',
                'class' => BodySystem::class,
                'file' => app_path('translations/body-systems.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'name' => 'Spanish',
                        'short_description' => 'description Spanish',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'name' => 'Portuguese',
                        'short_description' => 'description Portuguese',
                    ],
                ],
            ],
            [
                'type' => 'blends',
                'class' => Blend::class,
                'file' => app_path('translations/blends.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'fact' => 'Spanish Fact',
                        'emotion_1' => 'descriptor Spanish 1',
                        'emotion_2' => 'descriptor Spanish 2',
                        'emotion_3' => 'descriptor Spanish 3',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'fact' => 'Portuguese Fact',
                        'emotion_1' => 'descriptor Portuguese 1',
                        'emotion_2' => 'descriptor Portuguese 2',
                        'emotion_3' => 'descriptor Portuguese 3',
                    ],
                ],
            ],
            [
                'type' => 'supplements',
                'class' => Supplement::class,
                'file' => app_path('translations/supplements.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'fact' => 'Spanish fact',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'fact' => 'Portugese fact',
                    ],
                ],
            ],
            [
                'type' => 'ailments',
                'class' => Ailment::class,
                'file' => app_path('translations/ailments.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'name' => 'Spanish',
                        'short_description' => 'Spanish description',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'name' => 'Portuguese',
                        'short_description' => 'Portuguese description',
                    ],
                ],
            ],
            [
                'type' => 'symptoms',
                'class' => Ailment::class,
                'file' => app_path('translations/symptoms.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'name' => 'Spanish name',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'name' => 'Portuguese name',
                    ],
                ],
            ],
            [
                'type' => 'recipes',
                'class' => Recipe::class,
                'file' => app_path('translations/recipes.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'name' => 'Spanish name',
                        'body' => 'Instructions Spanish',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'name' => 'Portuguese name',
                        'body' => 'Instructions Portuguese',
                    ],
                ],
            ],
            [
                'type' => 'recipe-ingredients',
                'class' => RecipeIngredient::class,
                'file' => app_path('translations/recipe-ingredients.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'name' => 'Spanish',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'name' => 'Portuguese',
                    ],
                ],
            ],
            [
                'type' => 'recipe-categories',
                'class' => Category::class,
                'file' => app_path('translations/recipe-categories.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'name' => 'Spanish',
                        'short_description' => 'Spanish Desc',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'name' => 'Portuguese',
                        'short_description' => 'Portuguese Desc',
                    ],
                ],
            ],
            [
                'type' => 'supplement-ingredients',
                'class' => SupplementIngredient::class,
                'file' => app_path('translations/supplement-ingredients.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'name' => 'Spanish',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'name' => 'Portuguese',
                    ],
                ],
            ],
            [
                'type' => 'sourcing-methods',
                'class' => SourcingMethod::class,
                'file' => app_path('translations/sourcing-methods.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'name' => 'Spanish',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'name' => 'Portuguese',
                    ],
                ],
            ],
            [
                'type' => 'property-constituents',
                'class' => Tag::class,
                'file' => app_path('translations/property-constituents.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'name' => 'Spanish',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'name' => 'Portuguese',
                    ],
                ],
            ],
            [
                'type' => 'remedies',
                'class' => Remedy::class,
                'file' => app_path('translations/remedies.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'name' => 'Name Spanish',
                        'body' => 'body Spanish',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'name' => 'Name Portuguese',
                        'body' => 'body Portuguese',
                    ],
                ],
            ],
            [
                'type' => 'remedy-ingredients',
                'class' => RemedyIngredient::class,
                'file' => app_path('translations/remedy-ingredients.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'name' => 'ingredient Spanish',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'name' => 'Ingredient Portuguese',
                    ],
                ],
            ],
            [
                'type' => 'safety-information',
                'class' => SafetyInformation::class,
                'file' => app_path('translations/safety-information.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'description' => 'Spanish',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'description' => 'Portuguese',
                    ],
                ],
            ],
            [
                'type' => 'ailment-solutions',
                'class' => AilmentSolution::class,
                'file' => app_path('translations/ailment-solutions.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'uses_description' => 'Spanish',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'uses_description' => 'Portuguese',
                    ],
                ],
            ],
            [
                'type' => 'solutions',
                'class' => Solution::class,
                'file' => app_path('translations/solutions.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'description' => 'Spanish',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'description' => 'Portuguese',
                    ],
                ],
            ],
            [
                'type' => 'usages',
                'class' => Usage::class,
                'file' => app_path('translations/usages.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'description' => 'Spanish',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'description' => 'Portuguese',
                    ],
                ],
            ],
            [
                'type' => 'dashboard-cards',
                'class' => Card::class,
                'file' => app_path('translations/dashboard-cards.csv'),
                'translations' => [
                    config()->get('database.connections.languages.es') => [
                        'title' => 'Spanish Title',
                        'subtitle' => 'Spanish Subtitle',
                        'description' => 'Spanish Description',
                    ],
                    config()->get('database.connections.languages.pt') => [
                        'title' => 'Portuguese Title',
                        'subtitle' => 'Portuguese Subtitle',
                        'description' => 'Portuguese Description',
                    ],
                ],
            ],
        ];

        $type = $this->argument('type');
        $mapping = $mappings;
        if ($type !== 'all') {
            $mapping = array_filter($mappings, function ($map) use ($type) {
                return $map['type'] == $type;
            });
        }

        if (! $mapping) {
            echo "Unknown type. Available: oils, body-systems, blends, supplements, ailments, symptoms " . PHP_EOL;
            return;
        }

        foreach ($mapping as $map) {
            $data = (new FastExcel)->import($map['file']);
            // loop models
            foreach ($data as $d) {
                // loop db trans
                foreach ($map['translations'] as $key => $value) {
                    echo "Model " . $d['id'] . ' updating lang ' . $key , PHP_EOL;
                    config()->set('database.connections.mysql.database', $key);
                    DB::connection(env('DB_CONNECTION', 'mysql'))->reconnect();
                    $model = (new $map['class'])->find($d['id']);
                    if ($model) {
                        $model->flushEventListeners();
                        if ($model->timestamps) {
                            $model->timestamps = false;
                        }
                        foreach ($value as $vKey => $vValue) {
                            if (!empty($d[$vValue])){
                                $model[$vKey] = $d[$vValue];
                            }
                        }
                        $model->save();
                    }
                    echo "Complete" . PHP_EOL;
                }
            }
        }
    }
}
