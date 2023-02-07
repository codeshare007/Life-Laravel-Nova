<?php

namespace App\Console\Commands;

use App\Model;
use App\RecipeIngredient;
use App\RemedyIngredient;
use App\Services\LanguageDatabaseService;
use Illuminate\Support\Str;
use App\SupplementIngredient;
use Illuminate\Console\Command;

class SeparateIngredientNamesIntoQuantityAndMeasure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:separate-ingredient-names-into-quantity-and-measure';

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
        $this->languageDatabaseService->eachDatabase(function() {
            RecipeIngredient::chunk(50, function ($ingredients) {
                foreach ($ingredients as $ingredient) {
                    $this->separate($ingredient);
                }
            });
    
            RemedyIngredient::chunk(50, function ($ingredients) {
                foreach ($ingredients as $ingredient) {
                    $this->separate($ingredient);
                }
            });
    
            SupplementIngredient::chunk(50, function ($ingredients) {
                foreach ($ingredients as $ingredient) {
                    $ingredient->flushEventListeners();
                    $ingredient->timestamps = false;
    
                    $customName = '';
    
                    if ($ingredient->ingredientable_id === null) {
                        $customName = $ingredient->name;
                    }
            
                    $ingredient->update([
                        'custom_name' => $customName,
                    ]);
                }
            });
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
