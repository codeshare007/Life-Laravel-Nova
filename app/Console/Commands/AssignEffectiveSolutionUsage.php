<?php

namespace App\Console\Commands;

use App\BodySystem;
use App\Recipe;
use App\Solution;
use App\SolutionGroup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AssignEffectiveSolutionUsage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:assign-effective-solution-usage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto assign effective solution usages based on application';

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
        $usagesToUpdate = [];
        $usagesDesc = [
            'T' => 'Apply 1-2 drops to the area(s) of concern and/or bottoms of feet.',
            'TA' => 'Apply 1-2 drops to the area of concern. Inhale or diffuse.',
            'TI' =>	'Apply 1-2 drops to the area of concern. Take in a capsule or glass of water.',
            'TAI' => 'Apply 1-2 drops to the area of concern. Inhale or diffuse. Take in a capsule or glass of water.',
            'TIA' => 'Apply 1-2 drops to the area of concern. Take in a capsule or glass of water. Inhale or diffuse.',
            'A' => 'Inhale 1-2 drops from cupped hands or diffuse 3-4 drops from a diffuser.',
            'AI' => 'Inhale or diffuse. Take in a capsule or glass of water.',
            'AT' => 'Inhale or diffuse. Apply 1-2 drops to the area of concern.',
            'AIT' => 'Inhale or diffuse. Take in a capsule or glass of water. Apply 1-2 drops to the area of concern.',
            'ATI' => 'Inhale or diffuse. Take in a capsule or glass of water. Take in a capsule or glass of water.',
            'I' => 'Take 1-2 drops in a capsule, in a glass of water, or under the tongue.',
            'IT' => 'Take in a capsule or glass of water. Apply 1-2 drops to the area of concern.',
            'IA' => 'Take in a capsule or glass of water. Inhale or diffuse.',
            'ITA' => 'Take in a capsule or glass of water. Apply 1-2 drops to the area of concern. Inhale or diffuse.',
            'IAT' => 'Take in a capsule or glass of water. Inhale or diffuse. Apply 1-2 drops to the area of concern.',
        ];

        $groupAilmentSolutions = DB::table('ailment_solution')
            ->select('ailment_id', 'solution_id', 'uses_application')
            //->where('usage_id', '>', 0)
            ->get();

        $groupAilmentSolutions->mapToGroups(function($item, $key) {
            $combinationKey = collect(json_decode($item->uses_application))
                ->where('active', true)
                ->sortBy('position')
                ->pluck('name')
                ->map(function($application) {
                    return substr($application, 0, 1);
                })->implode('');
            return [$combinationKey => $item->ailment_id.'_'.$item->solution_id];
        })->each(function($combinations, $combinationKey) use ($usagesDesc, $usagesToUpdate) {
            //if (strlen($combinationKey) == 1) {
            if ($combinationKey) {
                foreach ($combinations as $ailmentSolution) {
                    $ailmentSolutionExp = explode('_', $ailmentSolution);

                    $ailmentSolution = DB::table('ailment_solution')->where([
                        'ailment_id' => $ailmentSolutionExp[0],
                        'solution_id' => $ailmentSolutionExp[1]
                    ]);

                    if ($ailmentSolution->exists() && isset($usagesDesc[$combinationKey])) {
                        //$usagesToUpdate[$ailmentSolution->value('ailment_id').'_'.$ailmentSolution->value('solution_id')] = $usagesDesc[$combinationKey];
                        $ailmentSolution->update(['usage_description' => $usagesDesc[$combinationKey]]);
                    } else {
                        $this->info("Ailment solution with combinationKey: $combinationKey doesn\'t exist");
                    }
                }
                //dump($combinationKey, count($usagesToUpdate));
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
