<?php

namespace Wqa\PdfExtract\BodySystem;

use Illuminate\Database\Eloquent\Collection;
use Wqa\PdfExtract\Area;
use Wqa\PdfExtract\Helper;
use Wqa\PdfExtract\Models\PdfExtractData;
use Wqa\PdfExtract\Models\PdfExtractRelatedProperties;
use Wqa\PdfExtract\Pdf;
use Wqa\PdfExtract\SearchIndex;

class RelatedProperties extends Area
{
    protected $config;
    protected $layout;

    public function prepare($data, $page)
    {
        $relatedProperties = $this->extractRelatedProperties($data);

        return $this->storeData($relatedProperties, $page);
    }

    /**
     * @param $data
     */
    public function storeData($relatedProperties, $page)
    {
        foreach ($relatedProperties as $relatedProperty) {
            $name = empty(trim($relatedProperty['name'])) ? 'NO NAME TO EXTRACT' : $relatedProperty['name'];

            if (count($relatedProperty['solutions'])) {
                PdfExtractRelatedProperties::updateOrCreate(
                    ['pdf_id' => 1, 'page' => $page, 'name' => $name],
                    [
                        'content' => $relatedProperty['content'],
                        'solutions' => implode(', ', $relatedProperty['solutions']),
                        'status' => count($relatedProperty['solutions']) > 0 ? 'Completed' : 'Review'
                    ]
                );
            }
        }

        return $relatedProperties;
    }

    public function search($data, SearchIndex $search)
    {
        $data = $this->filterData($data, 'Related Properties');

        foreach ($data as $page => $collection) {

            $startindex = $search->correctCollectionIndex($search->getCollectionStartIndex($collection, 'Related Properties'), $collection, $page);
            //$endIndex = $search->correctCollectionIndex($search->getCollectionEndIndex($collection, 'BLENDS'), $collection, $page);
            $endIndex = $search->getCollectionEndIndex($collection, 'BLENDS');

            $data[$page] = $this->prepare($collection->slice($startindex+1, $endIndex), $page);
        }

        //dd($data->get(32));
        return $data;
    }

    protected function extractRelatedProperties($data)
    {
        $propertiesArr = [];

        // Fonts need to be added to PDF config associated with the area to extract
        $properties = $data->map(function($item){
            $fontName = explode('+', $item['FontName']);
            $item['FontName'] = $fontName[1];
            return $item;
        })->groupBy('FontName', true)->get('BrandonText-Medium');

        //dd($data, $properties);

        if ($properties) {
            $properties = $properties->toArray();
            //dump($properties);
            foreach ($properties as $key => $property) {
                next($properties);

                $extracted = $this->extractContent($data, $key, key($properties), $property['Text']);
                $propertiesArr[$key] = [
                    'name' => $property['Text'],
                    'content' => $extracted['content'],
                    'solutions' => $extracted['solutions']
                ];
            }
        }

        //dd($propertiesArr);

        return $propertiesArr;
    }

    protected function extractContent($data, $currentKey, $nextKey, $property)
    {
        $limit = !is_null($nextKey) ? $nextKey-$currentKey-1 : null;
        $start = $data->keys()->search($currentKey)+1;
        //dump($property, 'currentKey:'.$currentKey, 'nextKey:'.$nextKey, 'limit:'.$limit, $data->slice($start, $limit), $start, 'END');

        $merged = ltrim(trim($data->slice($start, $limit)->pluck('Text')->implode('')), '-');
        $content = collect(array_map('UCWORDS', array_map('TRIM', explode(',', $merged))));

        return [
            'content' => $content,
            'solutions' => $this->cleanupSolutionsList($content)
        ];
    }
}
