<?php

namespace App\Console\Commands;

use App\Oil;
use App\Blend;
use App\Model;
use App\Ailment;
use App\BodySystem;
use App\Supplement;
use App\Enums\AilmentType;
use Illuminate\Console\Command;
use Wqa\PdfExtract\Models\PdfExtractConditions;

class AilmentsConditionsImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:ailments-conditions-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ailments Conditions Import';

    private $ailmentsMatched = 0;
    private $conditionsCreated = 0;

    private $extractedConditions;
    private $ailments;

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
        $this->extractedConditions = PdfExtractConditions::whereStatus('Completed')->orderBy('name')->get()->map(function($condition) {
            $condition['normalizedName'] = $this->normalize($condition->name);
            return $condition;
        });

        $this->ailments = Ailment::all()->map(function($ailment) {
            $ailment['normalizedName'] = $this->normalize($ailment->name);
            return $ailment;
        });
        
        $this->extractedConditions->each(function($extractedCondition) {

            // Get the body system
            $bodySystem = $this->getBodySystemByName($extractedCondition->body_system);

            // Get or create the ailment
            $ailment = $this->findUniqueAilmentForBodySystemOrCreateCondition($extractedCondition, $bodySystem);

            // Make sure the ailment is related to the body system
            $ailment->bodySystems()->syncWithoutDetaching([$bodySystem->id]);

            // Add the related body systems to the ailment
            $this->addRelatedBodySystemsToAilment($ailment, $extractedCondition);

            // Relate the oils/blends/supplements as secondary solutions
            $this->relateSecondarySolutions($ailment, $extractedCondition);
        });
        
        $this->info("Existing Ailments matched: $this->ailmentsMatched");
        $this->info("Conditions created: $this->conditionsCreated");
    }

    private function normalize($string)
    {
        return str_slug($string, '');
    }

    /**
     * Returns the ailment if one exists with the same name and body system, otherwise creates the ailment (as a condition).
     *
     * @param PdfExtractConditions $extractedCondition
     * @param BodySystem $bodySystem
     * @return Ailment
     */
    private function findUniqueAilmentForBodySystemOrCreateCondition(PdfExtractConditions $extractedCondition, BodySystem $bodySystem): Ailment
    {
        $ailmentsMatchingName = $this->ailments->where('normalizedName', $extractedCondition->normalizedName);

        if ($ailmentsMatchingName->count() > 1) {
            $ailment = Ailment::whereIn('id', $ailmentsMatchingName->pluck('id'))->whereHas('bodySystems', function ($query) use ($bodySystem) {
                $query->where('id', $bodySystem->id);
            })->first();
        } else {
            $ailment = $ailmentsMatchingName->first();
        }

        if ($ailment && $ailment->bodySystems->contains($bodySystem)) {

            $this->ailmentsMatched++;
            $this->info("Ailment found: $extractedCondition->name ($bodySystem->name)");

            return $ailment;

        } else {
            return $this->createCondition($extractedCondition);
        }
    }

    private function createCondition(PdfExtractConditions $extractedCondition): Ailment
    {
        $createdCondition = Ailment::create([
            'name' => str_replace('*', '', $extractedCondition->name),
            'ailment_type' => AilmentType::Symptom,
        ]);

        $createdCondition['normalizedName'] = $this->normalize($createdCondition->name);
        $this->ailments->add($createdCondition);

        $this->conditionsCreated++;
        $this->info("Condition $extractedCondition->name Added!");

        return $createdCondition;
    }

    private function getBodySystemByName(string $name): BodySystem
    {
        // Some of them are labelled incorrectly in the table, so let's fix 'em first.
        if ($name === 'Addictions') {
            $name = 'Addiction';
        } else if ($name === 'Digestive & Intestinal') {
            $name = 'Digestive';
        } else if ($name === 'Pain & Inflamation') {
            $name = 'Pain & Inflammation';
        } else if ($name === '133') {
            $name = 'Stress';
        }

        $bodySystem = BodySystem::where('name', $name)->first();

        if (! $bodySystem) {
            $this->error('No body system found with the name "' . $name . '".');
            die();
        }

        return $bodySystem;
    }

    private function relateSecondarySolutions(Ailment $ailment, PdfExtractConditions $extractedCondition): void
    {
        collect(explode(', ', $extractedCondition->solutions))->filter()->map(function ($solutionName) use ($ailment) {
            $solution = $this->getSolutionByName($solutionName);

            $ailment->secondarySolutions()->firstOrCreate([
                'solutionable_type' => get_class($solution),
                'solutionable_id' => $solution->id,
            ]);
        });
    }

    private function getSolutionByName(string $name): Model
    {
        return 
            Oil::where('name', $name)->first() ??
            Blend::where('name', $name)->first() ??
            Supplement::where('name', $name)->first() ??
            dd('Unable to find a Oil, Blend or Supplement for ' . $name);
    }

    private function addRelatedBodySystemsToAilment(Ailment $ailment, PdfExtractConditions $extractedCondition): void
    {
        collect(explode(', ', $extractedCondition->linked_body_system))->filter()->map(function ($bodySystemName) use ($ailment) {
            $bodySystem = $this->getBodySystemByName(trim($bodySystemName));

            $ailment->relatedBodySystems()->syncWithoutDetaching($bodySystem->id);
        });
    }
}
