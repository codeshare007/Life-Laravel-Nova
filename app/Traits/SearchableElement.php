<?php

namespace App\Traits;


use Laravel\Scout\Searchable;
use TeamTNT\TNTSearch\Indexer\TNTIndexer;

trait SearchableElement
{
    use Searchable;

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
