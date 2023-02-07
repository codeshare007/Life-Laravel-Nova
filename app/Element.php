<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Element extends Model
{
    protected $table = 'global_elements';

    protected $casts = [
        'id' => 'string'
    ];

    protected $fillable = [
        'id',
        'element_type',
        'element_id',
    ];

    public static $ignorePropagationTypes = [
        UserGeneratedContent::class,
        RecipeIngredient::class,
        RemedyIngredient::class,
    ];

    public function elementDetails()
    {
        return $this->morphTo('elementDetails', 'element_type', 'element_id');
    }
}
