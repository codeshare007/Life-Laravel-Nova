<?php

namespace Wqa\PdfExtract;

use App\Blend;
use App\Oil;
use App\Supplement;
use Illuminate\Support\Str;

abstract class Area
{
    protected $data = [];

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    protected function filterData($data, $query)
    {
        return $data->groupBy('PageNo')->filter(function($items, $page) use ($query) {
            return $items->search(function($item) use ($page, $query) {
                return Str::endsWith($item['Text'], $query);
            }) !== false;
        });
    }

    protected function cleanupSolutionsList($solutionsList)
    {
        $solutionsList = $this->replaceLegacyBlendNames($solutionsList);

        $oils = Oil::all()->pluck('name');
        $blends = Blend::all()->pluck('name');
        $supplements = Supplement::all()->pluck('name');
        $allowedSolutions = collect($oils)->merge($blends)->merge($supplements)->all();

        return $solutionsList->filter(function($item) use ($allowedSolutions) {
            return in_array($item, $allowedSolutions);
        })->all();
    }

    protected function replaceLegacyBlendNames($solutionsList)
    {
        $blends = [
            'ANTI-AGING BLEND' => 'Immortelle',
            'CAPTIVATING BLEND' => 'Beautiful®',
            'CELLULAR COMPLEX BLEND' => 'DDR Prime®',
            'CENTERING BLEND' => 'Align®',
            'CHILDREN\'S COURAGE BLEND' => 'Brave™',
            'CHILDREN\'S FOCUS BLEND' => 'Thinker™',
            'CHILDREN\'S GROUNDING BLEND' => 'Steady™',
            'CHILDREN\'S PROTECTIVE BLEND' => 'Stronger™',
            'CHILDREN\'S RESTFUL BLEND' => 'Calmer™',
            'CHILDREN\'S SOOTHING BLEND' => 'Rescuer™',
            'CLEANSING BLEND' => 'Purify',
            'COMFORTING BLEND' => 'Console®',
            'DETOXIFICATION BLEND' => 'Zendocrine®',
            'DIGESTION BLEND' => 'DigestZen®',
            'ENCOURAGING BLEND' => 'Motivate®',
            'ENLIGHTENING BLEND' => 'Arise™',
            'FOCUS BLEND' => 'InTune®',
            'GROUNDING BLEND' => 'Balance®',
            'HOLIDAY BLEND' => 'Holiday Joy®',
            'INSPIRING BLEND' => 'Passion®',
            'INVIGORATING BLEND' => 'Citrus Bliss®',
            'JOYFUL BLEND' => 'Elevation™',
            'MASSAGE BLEND' => 'AromaTouch®',
            'MEN\'S FORTIFYING BLEND' => 'Amavi®',
            'METABOLIC BLEND' => 'Slim & Sassy®',
            'PROTECTIVE BLEND' => 'On Guard®',
            'REASSURING BLEND' => 'Peace®',
            'RENEWING BLEND' => 'Forgive®',
            'REPELLENT BLEND' => 'TerraShield®',
            'RESPIRATION BLEND' => 'Breathe®',
            'RESTFUL BLEND' => 'Serenity®',
            'SKIN CLEARING BLEND' => 'HD Clear®',
            'SOOTHING BLEND' => 'Deep Blue®',
            'STEADYING BLEND' => 'Anchor®',
            'TENSION BLEND' => 'PastTense®',
            'UPLIFTING BLEND' => 'Cheer®',
            'WOMEN\'S MONTHLY BLEND' => 'ClaryCalm®',
            'WOMEN\'S PERFUME BLEND' => 'Whisper®'
        ];

        return $solutionsList->map(function($item) use ($blends) {
            $item = str_replace('-', '', $item);
            $item = preg_replace('#\s*\(.+\)\s*#U', '', $item);
            if (str_contains($item, ';')) {
                $item = trim(substr($item, 0, strpos($item, ';')));
            }
            $itemUpper = strtoupper($item);
            return isset($blends[$itemUpper]) ? $blends[$itemUpper] : trim($item);
        });
    }
}