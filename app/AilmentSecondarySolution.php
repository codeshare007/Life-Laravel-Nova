<?php

namespace App;

use App\Traits\PolymorphicEventTrait;
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;

class AilmentSecondarySolution extends Model implements Sortable
{
    use SortableTrait;
    use PolymorphicEventTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    protected $fillable = [
        'solutionable_type',
        'solutionable_id',
    ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = [
        'ailment'
    ];

    public function solutionable()
    {
        return $this->morphTo();
    }

    public function ailment()
    {
        return $this->belongsTo(Ailment::class);
    }
}
