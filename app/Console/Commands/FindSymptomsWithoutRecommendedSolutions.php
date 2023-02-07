<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Ailment;
use App\Enums\AilmentType;

class FindSymptomsWithoutRecommendedSolutions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:find-symptoms-without-recommended-solutions';

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
        Ailment::where('ailment_type', AilmentType::Symptom)->withCount('secondarySolutions')->get()->filter(function(Ailment $symptom) {
            return $symptom->secondary_solutions_count == 0;
        })->pluck('id', 'name')->dd();
    }
}
