<?php

namespace App\Console\Commands;

use App\Oil;
use App\Blend;
use App\Solution;
use App\Supplement;
use App\Enums\SolutionType;
use Illuminate\Console\Command;

class AddSolutionableToSolutionBasedOnSolutionName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:add-solutionable-to-solution-based-on-solution-name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For each of the solutions, look up the related oil/blend/supplement and add it as the solutionable in favour of the name being hard coded.';

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
        $solutions = Solution::all();

        $solutions->each(function($solution) {
            if ($solution->type === SolutionType::Oil) {

                $solution->solutionable_id = Oil::where('name', $solution->name)->value('id') ?? 0;
                $solution->solutionable_type = Oil::class;

            } elseif ($solution->type === SolutionType::Supplement) {

                $solution->solutionable_id = Supplement::where('name', $solution->name)->value('id') ?? 0;
                $solution->solutionable_type = Supplement::class;

            } else {

                $solution->solutionable_id = Blend::where('name', $solution->name)->value('id') ?? 0;
                $solution->solutionable_type = Blend::class;

            }

            $solution->save();
        });
    }
}
