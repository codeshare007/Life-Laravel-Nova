<?php

namespace App\Console\Commands;

use App\BodySystem;
use App\Solution;
use App\Supplement;
use Illuminate\Console\Command;

class ParseSupplementsDescriptionForSolutions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:parse-supplements-description-for-solutions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses the supplements description field on each body system and creates supplement solution as appropriate.';

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
        BodySystem::chunk(20, function ($bodySystems) {
            $solutionIds = [];
            foreach ($bodySystems as $bodySystem) {
                $supplementsDescription = $bodySystem->supplements;

                if (str_contains($supplementsDescription, ',')) {
                    $lines = explode(',', $supplementsDescription);
                    $solutionIds[$bodySystem->id] = [];
                    foreach ($lines as $supplement) {
                        //dd($supplement);
                        $supplement = trim(str_replace([".", "’"], ["", "'"], $supplement));
                        $solutionId = Solution::where('name', $supplement)->value('id') ?? $supplement;
                        if ($solutionId > 0) {
                            $solutionIds[$bodySystem->id][] = $solutionId;
                        }
                    }
                    //$bodySystem->solutions()->sync($solutionIds[$bodySystem->id]);
                } else {
                    $this->error("Ignoring body system $bodySystem->name ($bodySystem->id) as it does not have a well formatted description");
                }
            }
            dd($solutionIds);
        });
    }
}
