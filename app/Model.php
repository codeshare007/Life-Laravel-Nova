<?php

namespace App;

use App\Traits\ModelNameTrait;
use Illuminate\Database\Eloquent\Model as ModelCore;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Model extends ModelCore implements Sortable
{
    use SortableTrait;
    use ModelNameTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    protected $perPage = 100;
}
