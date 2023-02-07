<?php

namespace Wqa\PdfExtract;

use Illuminate\Support\Str;

class SearchIndex
{
    public function getCollectionStartIndex($collection, $query)
    {
        return $collection->search(function($item) use ($query) {
            return Str::endsWith($item['Text'], $query);
        });
    }

    public function getCollectionEndIndex($collection, $query)
    {
        return $collection->search(function($item) use ($query) {
            return Str::endsWith($item['Text'], $query);
        });
    }

    public function getCollectionClosestIndex($itemToMove, $collection, $page)
    {
        $collection = $collection->filter(function ($item) use ($itemToMove, $page) {
            $diff = [
                'x' => abs($item['XPos'] - $itemToMove['XPos']),
                'y' => abs($item['YPos'] - $itemToMove['YPos'])
            ];
            return $diff['x'] < 70 && $diff['y'] < 30;
        });

        return $collection->keys()->first();
    }

    public function correctCollectionIndex($index, $collection, $page)
    {
        if ($itemToMove = $collection->get($index)) {

            $collection->splice($index, 1);
            $closestIndex = $this->getCollectionClosestIndex($itemToMove, $collection, $page);

//            if ($page == 2) {
//                dump($itemToMove, $closestIndex);
//            }

            if ($closestIndex !== false) {
                $collection->splice($closestIndex, 0, [$itemToMove]);
            }
            return $closestIndex;
        }
    }
}
