<?php

namespace App\Console\Commands;

use App\Ailment;
use App\Enums\AilmentType;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class FixConditionNameCase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:fix-condition-name-case';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes the condition names title case';

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
        Ailment::where('ailment_type', AilmentType::Symptom)->get()->each(function(Ailment $ailment) {

            if (! $this->isUpperCase($ailment->name)) {
                $ailment->name = Str::title($ailment->name);
                $ailment->name = ucwords($ailment->name, '/');
                $ailment->save();
            }

        });
    }

    private function isUpperCase(string $string)
    {
        $string = preg_replace("/[^A-Za-z0-9 ]/", '', $string);

        return strtoupper($string) == $string;
    }
}
