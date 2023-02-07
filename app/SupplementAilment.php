<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class SupplementAilment extends Model implements Sortable
{
    use SortableTrait;

    public $fillable = [
        'supplement_id',
        'ailment_id',
        'name',
        'description'
    ];

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    public function supplement()
    {
        return $this->belongsTo(
            Supplement::class
        );
    }

    public function ailment()
    {
        return $this->belongsTo(
            Ailment::class
        );
    }
}
