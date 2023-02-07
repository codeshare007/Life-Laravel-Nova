<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeletedElement extends Model
{
    protected $table = 'global_elements_deleted';

    protected $casts = [
        'id' => 'string',
        'element_attributes' => 'array',
    ];

    protected $fillable = [
        'id',
        'element_type',
        'element_id',
        'element_attributes',
    ];

    public static function createFromModel(Model $model): self
    {
        return self::create([
            'id' => $model->element->id,
            'element_type' => $model->element->element_type,
            'element_id' => $model->element->element_id,
            'element_attributes' => $model->unsetRelation('element')->toArray(),
        ]);
    }
}
