<?php

namespace App\Traits;

trait IngredientableTrait
{
    public function addIngredients($ingredients, $relation)
    {
        collect($ingredients)->each(function ($ingredient) use ($relation) {

            $id = null;
            if ($ingredient['resource_type'] !== 'CustomIngredient') {
                $type = 'App\\' . $ingredient['resource_type'];

                // fetch details form uuid
                if (!isset($ingredient['id'])) {
                    $model = (new $type);
                    $findIngredient = $model->where('uuid', $ingredient['uuid'])->first();
                    $ingredient['id'] = $findIngredient->id ?? null;
                }

                $id = $ingredient['id'];
            }

            $relation->create([
                'name' => $ingredient['quantity'] . ' ' . $ingredient['measure'] . ' ' . $ingredient['name'],
                'quantity' => $ingredient['quantity'],
                'measure' => $ingredient['measure'],
                'custom_name' => $id === null ? $ingredient['name'] : '',
                'ingredientable_type' => $type ?? null,
                'ingredientable_id' => $id ?? null,
            ]);
        });
    }
}
