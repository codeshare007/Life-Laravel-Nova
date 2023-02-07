<?php

namespace App\Console\Commands;

use App\BodySystem;
use App\Recipe;
use App\Solution;
use App\SolutionGroup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ParseSplitBodySystemSolutions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:parse-split-body-system-solutions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses and split body system solutions';

    /**
     * Assign solutions to body systems
     * @var array
     */
    protected $assignSolutionToBodySystem = [];

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
        // Run this one first comma seperated.
        // $this->splitSolutions(', ');

        // After uncomment above then run the remaining seperated by &
        // $this->splitSolutions(' & ');

        if ($this->assignSolutionToBodySystem) {
            $this->assignSolutions();
        }
        //dd($this->assignSolutionToBodySystem);
    }

    protected function splitSolutions($seperator)
    {
        Solution::chunk(20, function ($solutions) use ($seperator) {
            foreach ($solutions as $solution) {
                if (str_contains($solution->name, $seperator)) {

                    $bodySystemIds = DB::table('body_system_solution')->where('solution_id', $solution->id)->pluck('body_system_id')->toArray();
                    $splitSolutions = explode($seperator, $solution->name);

                    foreach ($splitSolutions as $solutionName) {
                        $newSolution = new Solution;
                        $newSolution->name = $solutionName;
                        $newSolution->type = $solution->type;
                        $newSolution->description = $solution->description;
                        if ($newSolution->save() && $bodySystemIds) {
                            $this->assignSolutionToBodySystem[$newSolution->id] = $bodySystemIds;
                        }
                    }
                    $solution->delete();

                } else {
                    $this->info("Ignoring solution $solution->name ($solution->id) as split is not required");
                }
            }
        });
    }

    protected function assignSolutions()
    {
        foreach($this->assignSolutionToBodySystem as $solutionId => $bodySystemIds) {
            foreach ($bodySystemIds as $bodySystemId) {

                $association = [
                    'body_system_id' => $bodySystemId,
                    'solution_id' => $solutionId,
                ];

                $row = DB::table('body_system_solution')->where($association);
                if (!$row->exists()) {
                    DB::table('body_system_solution')->insert($association);
                }
            }
        }
    }
}
