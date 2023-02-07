<?php

namespace App;

use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;

class CategoryPanel extends Model implements Sortable
{
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    protected $fillable = [
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
