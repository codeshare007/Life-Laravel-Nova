<?php

namespace App\Console\Commands;

use App\Recipe;
use App\SolutionGroup;
use Illuminate\Console\Command;

class ParseRecipeShortDescriptionForIngredients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:parse-recipe-short-description-for-ingredients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses the short description field on each recipe and creates ingredients as appropriate.';

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
        Recipe::chunk(20, function ($recipes) {
            $solutions = SolutionGroup::all();
            foreach ($recipes as $recipe) {
                $shortDescription = $recipe->short_description;

                if (starts_with($shortDescription, '- ')) {

                    $lines = explode("\n", $shortDescription);
                    $lines = array_map(function($line) {
                        $line = trim($line);
                        return str_replace_first('- ', '', $line);
                    }, $lines);

                    foreach ($lines as &$ingredientName) {
                        $solution = $this->extractSolutionFromIngredient($ingredientName, $solutions);

                        $solutionNames = array_map('strtolower', $solutions->pluck('name')->toArray());
                        $ingredientName = preg_replace('/\b('. implode('|', $solutionNames).')\b/', '[[$1]]', $ingredientName);

                        preg_match_all("/\[\[([^\]]*)\]\]/", $ingredientName, $matches);
                        if (isset($matches[1][0])) {
                            $solution = SolutionGroup::where('name', ucwords($matches[1][0]))->first();
                        }

                        if (!in_array($recipe->name, ['Avocado Salsa', 'Lemon/Lime Bars'])) {
                            $recipe->recipeIngredients()->create([
                                'name' => $ingredientName,
                                'ingredientable_type' => $solution->useable_type ?? null,
                                'ingredientable_id' => $solution->useable_id ?? null,
                            ]);
                        }
                    }
                } else {
                    $this->error("Ignoring recipe $recipe->name ($recipe->id) as it does not have a well formatted short description");
                }
            }
        });
    }

    protected function extractSolutionFromIngredient($ingredientName, $solutions)
    {
        $ingredientName = strtolower($ingredientName);

        return $solutions->first(function ($solution, $key) use ($ingredientName) {
            return str_contains($ingredientName, strtolower($solution->name));
        });
    }
}
