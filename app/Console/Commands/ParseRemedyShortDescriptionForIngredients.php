<?php

namespace App\Console\Commands;

use App\Remedy;
use App\SolutionGroup;
use Illuminate\Console\Command;

class ParseRemedyShortDescriptionForIngredients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:parse-remedy-short-description-for-ingredients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses the short description field on each remedy and creates ingredients as appropriate.';

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
        Remedy::chunk(20, function ($remedies) {
            $solutions = SolutionGroup::all();
            foreach ($remedies as $remedy) {
                $shortDescription = $remedy->short_description;

                if (starts_with($shortDescription, '- ')) {
                    $lines = explode("\n", $shortDescription);
                    $lines = array_map(function($line) {
                        $line = trim($line);
                        return str_replace_first('- ', '', $line);
                    }, $lines);

                    foreach ($lines as $ingredientName) {
                        $solution = $this->extractSolutionFromIngredient($ingredientName, $solutions);
                        $remedy->remedyIngredients()->create([
                            'name' => $ingredientName,
                            'ingredientable_type' => $solution->useable_type ?? null,
                            'ingredientable_id' => $solution->useable_id ?? null,
                        ]);
                    }
                } else {
                    $this->error("Ignoring remedy $remedy->name ($remedy->id) as it does not have a well formatted short description");
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
