<?php

namespace App\Console\Commands;

use App\Ailment;
use App\AilmentSolution;
use Illuminate\Console\Command;

class TransitionAilmentSolutionsToNewRelationshipType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:transition-ailment-solutions-to-new-relationship-type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        AilmentSolution::truncate();

        Ailment::all()->each(function($ailment) {

            // Foreach of the old solutions for the ailment
            $ailment->oldSolutions()->each(function($oldSolution) use ($ailment) {

                $newSolution = new AilmentSolution();
                $newSolution->sortable['sort_when_creating'] = false;

                $newSolution->ailment_id = $ailment->id;
                $newSolution->uses_description = $oldSolution->pivot->usage_description;
                $newSolution->uses_application = $oldSolution->pivot->uses_application;
                $newSolution->solutionable_type = $oldSolution->useable_type;
                $newSolution->solutionable_id = $oldSolution->useable_id;
                $newSolution->sort_order = $oldSolution->pivot->sort_order;

                $newSolution->save();
            });
        });
    }
}
