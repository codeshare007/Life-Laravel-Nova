<?php

namespace App\Console\Commands;

use App\Ailment;
use App\AilmentSolution;
use App\Enums\AilmentType;
use Illuminate\Console\Command;
use App\AilmentSecondarySolution;

class RemoveSupportingSolutionsWhichAlreadyExistUnderEffectiveSolutionsForAilments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:remove-supporting-solutions-which-already-exist-under-effective-solutions-for-ailments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
        $toDelete = [];

        AilmentSolution::all()->each(function(AilmentSolution $solution) use (&$toDelete) {
            $duplicateSecondarySolution = AilmentSecondarySolution::
                where('ailment_id', $solution->ailment_id)
                ->where('solutionable_type', $solution->solutionable_type)
                ->where('solutionable_id', $solution->solutionable_id)
                ->first();

            if ($duplicateSecondarySolution) {
                $this->info("Found " . $duplicateSecondarySolution->solutionable->name . " in " . $duplicateSecondarySolution->ailment->name);

                $toDelete[] = $duplicateSecondarySolution->id;
            }
        });

        $toDeleteCount = count($toDelete);

        if ($toDeleteCount > 0) {
            if ($this->confirm("Do you want to delete the $toDeleteCount secondary solutions?")) {
                AilmentSecondarySolution::destroy($toDelete);
                $this->info("Deleted $toDeleteCount secondary solutions.");
            } else {
                $this->info("Cancelled.");
            }
        } else {
            $this->info("There are no duplicates to delete.");
        }
    }
}
