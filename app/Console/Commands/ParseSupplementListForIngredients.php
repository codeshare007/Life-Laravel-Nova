<?php

namespace App\Console\Commands;

use App\Supplement;
use App\SolutionGroup;
use Illuminate\Console\Command;

class ParseSupplementListForIngredients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:parse-supplement-list-for-ingredients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses the temp ingredients_list on each supplement and creates ingredients as appropriate.';

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
        Supplement::chunk(20, function ($supplements) {
            foreach ($supplements as $supplement) {
                $list = json_decode($supplement->ingredients_list);

                foreach ($list as $ingredient) {
                    $solution = SolutionGroup::find($ingredient->value ?? false);
                    $supplement->supplementIngredients()->create([
                        'name' => $ingredient->label,
                        'ingredientable_type' => $solution->useable_type ?? null,
                        'ingredientable_id' => $solution->useable_id ?? null,
                    ]);
                }
            }
        });
    }
}
