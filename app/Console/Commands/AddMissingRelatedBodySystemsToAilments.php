<?php

namespace App\Console\Commands;

use App\Ailment;
use App\BodySystem;
use Illuminate\Console\Command;

class AddMissingRelatedBodySystemsToAilments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wqa:add-missing-related-body-systems-to-ailments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Missing Related Body Systems To Ailments';

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
        $items = collect(json_decode('[{"body_system":"Athletes","ailment_name":"Athlete\'s foot","linked_body_system":"Candida"},{"body_system":"Athletes","ailment_name":"Joint, inflammation/stiffness","linked_body_system":"Skeletal"},{"body_system":"Athletes","ailment_name":"Muscle, stiness/tension","linked_body_system":"Muscular"},{"body_system":"Athletes","ailment_name":"Nerve damage","linked_body_system":"Nervous"},{"body_system":"Athletes","ailment_name":"Nerve pain","linked_body_system":"Nervous"},{"body_system":"Athletes","ailment_name":"Pulse, rapid","linked_body_system":"Cardiovascular"},{"body_system":"Autoimmune","ailment_name":"Blood in stool","linked_body_system":"Digestive & Intestinal"},{"body_system":"Autoimmune","ailment_name":"Multiple miscarriages","linked_body_system":"Women\'s Health"},{"body_system":"Autoimmune","ailment_name":"Numbness or tingling in the hands or feet","linked_body_system":"Nervous"},{"body_system":"Autoimmune","ailment_name":"Paralysis, facial","linked_body_system":"Nervous"},{"body_system":"Blood Sugar","ailment_name":"Digestive issues/chronic constipation or diarrhea","linked_body_system":"Digestion & Intestinal"},{"body_system":"Blood Sugar","ailment_name":"Nausea","linked_body_system":"Digestion & Intestinal"},{"body_system":"Blood Sugar","ailment_name":"Nerve damage","linked_body_system":"Nervous"},{"body_system":"Blood Sugar","ailment_name":"Sleep, difficulty","linked_body_system":"Sleep"},{"body_system":"Brain","ailment_name":"Alzheimer\'s","linked_body_system":"Focus & Concentration"},{"body_system":"Brain","ailment_name":"Brain, aging","linked_body_system":"Brain Above"},{"body_system":"Brain","ailment_name":"Parasites","linked_body_system":"Parasites"},{"body_system":"Brain","ailment_name":"Parkinson\'s","linked_body_system":"Addictions"},{"body_system":"Children","ailment_name":"Foot fungus (Athlete\'s foot)","linked_body_system":"Candida"},{"body_system":"Children","ailment_name":"Whooping Cough, spastic/persistent cough","linked_body_system":"Respiratory"},{"body_system":"Detoxification","ailment_name":"Blood toxicity","linked_body_system":"Cardiovascular"},{"body_system":"Detoxification","ailment_name":"Nerve toxicity","linked_body_system":"Nervous"},{"body_system":"Eating Disorders","ailment_name":"Emotionality/overly emotional","linked_body_system":"Mood & Behavior"},{"body_system":"Eating Disorders","ailment_name":"Expressing/managing emotions/feelings, difficulty","linked_body_system":"Mood & Behavior"},{"body_system":"Eating Disorders","ailment_name":"Hormone imbalance","linked_body_system":"Mens Health, Womens Health"},{"body_system":"Eating Disorders","ailment_name":"Menstrual irregularities or loss of menstruation (amenorrhea)","linked_body_system":"Womens Health"},{"body_system":"Eating Disorders","ailment_name":"Sores, scars, calluses on knuckles or hands","linked_body_system":"Integumentary"},{"body_system":"Endocrine","ailment_name":"Difficulty concentrating","linked_body_system":"Focus & Concentration"},{"body_system":"Endocrine","ailment_name":"Blood sugar issues (too high/too low)","linked_body_system":"Blood Sugar"},{"body_system":"Endocrine","ailment_name":"Urinary issues associated with pancreatic/blood sugar imbalances","linked_body_system":"Blood Sugar, Urinary"},{"body_system":"Endocrine","ailment_name":"Body temp, cold","linked_body_system":"Cardiovascular"},{"body_system":"Endocrine","ailment_name":"Cold intolerance","linked_body_system":"Cardiovascular"},{"body_system":"Endocrine","ailment_name":"Thyroid hormones, false","linked_body_system":"Candida"},{"body_system":"Energy & Vitality","ailment_name":"Adrenal exhaustion/fatigue","linked_body_system":"Endocrine"},{"body_system":"Integumentary","ailment_name":"Fungus�邸梯�_","linked_body_system":"Candida"},{"body_system":"Integumentary","ailment_name":"Athlete\'s foot","linked_body_system":"Candida"},{"body_system":"Intimacy","ailment_name":"Endometriosis","linked_body_system":"Womens Health"},{"body_system":"Intimacy","ailment_name":"Impotence","linked_body_system":"Mens Health"},{"body_system":"Intimacy","ailment_name":"Sex drive, low (low libido)","linked_body_system":"Men\'s Health, Women\'s Health"},{"body_system":"Nervous System","ailment_name":"Dry, itchy, red/redness, tear duct (blocked), watery","linked_body_system":"Respiratory"},{"body_system":"Nervous System","ailment_name":"Huntington\'s disease","linked_body_system":"Autoimmune"},{"body_system":"Pain & Inflamation","ailment_name":"Facial nerve","linked_body_system":"Nervous"},{"body_system":"Pain & Inflamation","ailment_name":"Nerve damage","linked_body_system":"Nervous"},{"body_system":"Pain & Inflamation","ailment_name":"Phantom pains","linked_body_system":"Nervous"},{"body_system":"Pain & Inflamation","ailment_name":"Spinal cord injury","linked_body_system":"Nervous"},{"body_system":"Pain & Inflamation","ailment_name":"Tingling/numbness","linked_body_system":"Nervous"},{"body_system":"Skeletal","ailment_name":"Sciatic issues","linked_body_system":"Nervous"},{"body_system":"Sleep","ailment_name":"Caffeine (avoid consuming within four to six hours of sleep)","linked_body_system":"Addictions"},{"body_system":"Sleep","ailment_name":"CPAP machine, difficulties with","linked_body_system":"Respiratory"},{"body_system":"Sleep","ailment_name":"Hormone imbalances","linked_body_system":"Mens Health, Womens Health"},{"body_system":"Sleep","ailment_name":"Insomnia, nervous tension","linked_body_system":"Nervous"},{"body_system":"Sleep","ailment_name":"Jet lag, can\'t go to sleep","linked_body_system":"Sleep"},{"body_system":"Sleep","ailment_name":"Stimulants, use of (medications, caffeine, energy drinks, supplements)","linked_body_system":"Addictions"},{"body_system":"Weight","ailment_name":"Appetite, excess","linked_body_system":"Weight"},{"body_system":"Weight","ailment_name":"Hunger pains","linked_body_system":"Digestion & Intestinal"},{"body_system":"Weight","ailment_name":"Thyroid imbalances","linked_body_system":"Endocrine"},{"body_system":"Digestive & Intestinal","ailment_name":"Yeast","linked_body_system":"Candida"},{"body_system":"Immune & Lymphatic","ailment_name":"Earache","linked_body_system":"Respiratory"},{"body_system":"Immune & Lymphatic","ailment_name":"Fungus/candida","linked_body_system":"Candida"},{"body_system":"Immune & Lymphatic","ailment_name":"Lymph, congestion/stagnation","linked_body_system":"Respiratory"},{"body_system":"Immune & Lymphatic","ailment_name":"Malaria","linked_body_system":"Parasites"},{"body_system":"Immune & Lymphatic","ailment_name":"Night sweats","linked_body_system":"Womens Health, Endocrine"},{"body_system":"Immune & Lymphatic","ailment_name":"Parasites","linked_body_system":"Parasites"},{"body_system":"Immune & Lymphatic","ailment_name":"Ringworm","linked_body_system":"Integumentary"},{"body_system":"Immune & Lymphatic","ailment_name":"Throat, sore","linked_body_system":"Respiratory"},{"body_system":"Immune & Lymphatic","ailment_name":"Tonsillitis","linked_body_system":"Oral Health"},{"body_system":"Integumentary","ailment_name":"Eczema/psoriasis","linked_body_system":"Candida"},{"body_system":"Integumentary","ailment_name":"Fungus","linked_body_system":"Candida"},{"body_system":"Integumentary","ailment_name":"Rashes","linked_body_system":"Candida"}]'))->map(function($item) {
            $item->normalizedName = $this->normalize($item->ailment_name);
            return $item;
        });

        $this->ailments = Ailment::all()->map(function($ailment) {
            $ailment['normalizedName'] = $this->normalize($ailment->name);
            return $ailment;
        });

        $items->each(function($item) {
            $bodySystem = $this->getBodySystemByName($item->body_system);
            $ailment = $this->findUniqueAilmentForBodySystem($item, $bodySystem);
            $this->addRelatedBodySystemsToAilment($ailment, $item);
        });
    }

    private function normalize($string)
    {
        return str_slug($string, '');
    }

    private function findUniqueAilmentForBodySystem($item, BodySystem $bodySystem): Ailment
    {
        $ailmentsMatchingName = $this->ailments->where('normalizedName', $item->normalizedName);

        if ($ailmentsMatchingName->count() > 1) {
            $ailment = Ailment::whereIn('id', $ailmentsMatchingName->pluck('id'))->whereHas('bodySystems', function ($query) use ($bodySystem) {
                $query->where('id', $bodySystem->id);
            })->first();
        } else {
            $ailment = $ailmentsMatchingName->first();
        }

        if ($ailment && $ailment->bodySystems->contains($bodySystem)) {
            return $ailment;
        }

        $this->error('Could not find an ailment for ' . $item->ailment_name . 'in the body system ' . $item->body_system);
        die();
    }

    private function getBodySystemByName(string $name): BodySystem
    {
        // Some of them are labelled incorrectly in the table, so let's fix 'em first.
        if ($name === 'Addictions') {
            $name = 'Addiction';
        } else if ($name === 'Digestive & Intestinal' || $name === 'Digestion & Intestinal') {
            $name = 'Digestive';
        } else if ($name === 'Pain & Inflamation') {
            $name = 'Pain & Inflammation';
        } else if ($name === '133') {
            $name = 'Stress';
        } else if ($name === 'Nervous') {
            $name = 'Nervous System';
        } else if ($name === 'Focus & Concentration') {
            $name = 'Focus';
        } else if ($name === 'Brain Above') {
            $name = 'Brain';
        } else if ($name === 'Mens Health') {
            $name = 'Men\'s Health';
        } else if ($name === 'Womens Health') {
            $name = 'Women\'s Health';
        }

        $bodySystem = BodySystem::where('name', $name)->first();

        if (! $bodySystem) {
            $this->error('No body system found with the name "' . $name . '".');
            die();
        }

        return $bodySystem;
    }

    private function addRelatedBodySystemsToAilment(Ailment $ailment, $item): void
    {
        collect(explode(', ', $item->linked_body_system))->filter()->map(function ($bodySystemName) use ($ailment) {
            $bodySystem = $this->getBodySystemByName(trim($bodySystemName));

            $ailment->relatedBodySystems()->syncWithoutDetaching($bodySystem->id);
        });
    }
}
