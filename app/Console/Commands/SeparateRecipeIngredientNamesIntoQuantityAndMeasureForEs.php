<?php

namespace App\Console\Commands;

use App\RecipeIngredient;
use App\Enums\UserLanguage;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Services\LanguageDatabaseService;

class SeparateRecipeIngredientNamesIntoQuantityAndMeasureForEs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:separate-recipe-ingredient-names-into-quantity-and-measure-for-es';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For each ingredient, attempt to split the name field into "quantity", "measure" and "custom_name" if required.';

    protected $languageDatabaseService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(LanguageDatabaseService $languageDatabaseService)
    {
        $this->languageDatabaseService = $languageDatabaseService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->languageDatabaseService->setLanguage(UserLanguage::Spanish());

        RecipeIngredient::chunk(50, function ($ingredients) {
            foreach ($ingredients as $ingredient) {
                $this->separate($ingredient);
            }
        });
    }

    protected function startsWithNumber($str): bool
    {
        return Str::startsWith($str, [1, 2, 3, 4, 5, 6, 7, 8, 9, 0, '½']);
    }

    protected function separate($ingredient)
    {
        $parts = explode(' ', $ingredient->name);

        $ingredient->flushEventListeners();
        $ingredient->timestamps = false;

        $quantity = '';
        $measure = '';
        $customName = '';

        if ($this->startsWithNumber($ingredient->name)) {
            $quantity = $parts[0];
            $measure = $parts[1];

            if ($this->startsWithNumber($measure)) {
                $quantity = $parts[0] . ' ' . $parts[1];
                $measure = $parts[2];
            }
        } else {
            $customName = $ingredient->name;
        }

        if ($ingredient->ingredientable_id === null) {
            $customName = trim(ltrim(trim(ltrim($ingredient->name, $quantity)), $measure));
        } else {
            $customName = '';
        }

        $ingredient->update([
            'quantity' => $quantity,
            'measure' => $measure,
            'custom_name' => $customName,
        ]);
    }
}
