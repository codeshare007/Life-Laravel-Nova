<?php

namespace Wqa\PdfExtract\BodySystem;

use Wqa\PdfExtract\Area;
use Wqa\PdfExtract\Helper;
use Wqa\PdfExtract\Layout;
use Wqa\PdfExtract\Models\PdfExtractConditions;
use Wqa\PdfExtract\Models\PdfExtractData;
use Wqa\PdfExtract\Pdf;
use Wqa\PdfExtract\SearchIndex;

class Conditions extends Area
{
    protected $config;
    protected $layout;

    public function prepare($data, $page)
    {
        $conditions = $this->extractConditions($data);
        return $this->storeData($conditions, $page);
    }

    /**
     * @param $data
     */
    public function storeData($conditions, $page)
    {
        foreach ($conditions as $condition) {
            $name = empty(trim($condition['name'])) ? 'NO NAME TO EXTRACT' : $condition['name'];

            PdfExtractConditions::updateOrCreate(
                ['pdf_id' => 1, 'page' => $page, 'name' => $name],
                [
                    'body_system' => $this->mapBodySystemToPage($page),
                    'content' => $condition['content'],
                    'solutions_nb' => count($condition['solutions']),
                    'solutions' => implode(', ', $condition['solutions']),
                    'linked_body_system' => utf8_decode($condition['bodySystem']) ?? '---',
                    'status' => count($condition['solutions']) > 0 || !empty($condition['bodySystem']) ? 'Completed' : 'Review'
                ]
            );
        }

        return $conditions;
    }

    public function search($data, SearchIndex $search, $params = [])
    {
        if (isset($params['page']) && $params['page'] > 0) {
            $data = $data->groupBy('PageNo');
        } else {
            $data = $this->filterData($data, 'Conditions');
        }

        foreach ($data as $page => $collection) {

            if (isset($params['page']) && $params['page'] > 0) {
                $data[$page] = $this->prepare($collection, $page);
            } else {
                $startindex = $search->correctCollectionIndex($search->getCollectionStartIndex($collection,
                    'Conditions'), $collection, $page);
                $endIndex = $search->getCollectionEndIndex($collection, 'USAGE TIPS:');

                $data[$page] = $this->prepare($collection->slice($startindex + 1, $endIndex - 1), $page);
            }

        }

        return $data;
    }

    protected function extractConditions($data)
    {
        $conditionsArr = [];

        // Fonts need to be added to PDF config associated with the area to extract
        $conditions = $data->map(function($item){
            $fontName = explode('+', $item['FontName']);
            $item['FontName'] = $fontName[1];
            return $item;
        })->groupBy('FontName', true)->get('BrandonText-Medium');

        if ($conditions) {
            $conditions = $conditions->toArray();
            foreach ($conditions as $key => $condition) {
                next($conditions);

                $condition['Text'] = str_replace('*', '', $condition['Text']);
                $extracted = $this->extractContent($data, $key, key($conditions));
                $conditionsArr[$key] = [
                    'name' => $condition['Text'],
                    'content' => $extracted['content'],
                    'solutions' => $extracted['solutions'],
                    'bodySystem' => $extracted['bodySystem']
                ];
            }
        }

        return $conditionsArr;
    }

    protected function extractContent($data, $currentKey, $nextKey)
    {
        $limit = !is_null($nextKey) ? $nextKey-$currentKey-1 : null;
        $start = $data->keys()->search($currentKey)+1;

        $merged = utf8_decode(ltrim(trim($data->slice($start, $limit)->pluck('Text')->implode('')), '-'));
        $content = collect(array_map('UCWORDS', array_map('TRIM', explode(',', $merged))));

        return [
            'content' => $content,
            'solutions' => $this->cleanupSolutionsList($content),
            'bodySystem' => $this->extractBodySystem($content)
        ];
    }

    protected function extractBodySystem($content)
    {
        $bodySystem = $content->filter(function($item) {
            return str_contains(strtoupper($item), 'SEE ');
        })->map(function($item){
            return trim(substr($item, strpos(strtoupper($item), 'SEE ')+4));
        })->implode(' ');

        return $bodySystem;
    }

    protected function mapBodySystemToPage($page)
    {
        $bodySystems = [
            18 => 'Candida',
            32 => 'Addictions',
            33 => 'Allergies',
            34 => 'Athletes',
            35 => 'Autoimmune',
            36 => 'Blood Sugar',
            37 => 'Brain',
            38 => 'Cardiovascular',
            40 => 'Cellular Health',
            41 => 'Children',
            42 => 'Detoxification',
            49 => 'Digestive & Intestinal',
            50 => 'Digestive & Intestinal',
            53 => 'Eating Disorders',
            57 => 'Endocrine',
            60 => 'Energy & Vitality',
            64 => 'First Aid',
            68 => 'Focus',
            73 => 'Immune & Lymphatic',
            74 => 'Immune & Lymphatic',
            77 => 'Integumentary',
            78 => 'Integumentary',
            83 => 'Intimacy',
            85 => 'Limbic',
            89 => 'Men\'s Health',
            92 => 'Mood & Behavior',
            96 => 'Muscular',
            101 => 'Nervous System',
            104 => 'Oral Health',
            107 => 'Pain & Inflamation',
            111 => 'Parasites',
            115 => 'Pregnancy',
            122 => 'Respiratory',
            127 => 'Skeletal',
            130 => 'Sleep',
            134 => 'Stress',
            137 => 'Urinary',
            140 => 'Weight',
            146 => 'Women\'s Health'
        ];

        return $bodySystems[$page] ?? $page;
    }
}