<?php

namespace App\Http\Resources\v2_1;

use App\Category;
use App\Enums\UserGeneratedContentStatus;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserCommunityRecipe2.1",
 *     description="Individual basic resource response",
 *     type="object",
 *     title="User Community Recipe V2.1",
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="uuid",
 *          type="integer",
 *          description="Resource UUID"
 *     ),
 * )
 */
class UserCommunityRecipe extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'uuid' => $this->uuid,
            'type' => 'Recipe',
            'name' => $this->name,
            'status' => $this->is_public ? UserGeneratedContentStatus::getKey($this->status) : UserGeneratedContentStatus::getKey(0),
            'public_uuid' => $this->recipe ? $this->recipe->uuid : null,
        ];

        if (!currentUserRequest($request)) {
            $data['instructions'] = $this->content['instructions'];
            $data['ingredients'] = isset($this->content['ingredients']) ?
                is_array($this->content['ingredients']) ?
                    collect($this->content['ingredients'])->map(function ($ingredient) {
                        $className =  'App\\' . $ingredient['resource_type'];
                        if ($ingredient['resource_type'] !== 'CustomIngredient') {
                            // legacy items store 'id' in the ingredients
                            if (isset($ingredient['id'])) {
                                $model = (new $className)->find($ingredient['id']);
                                $ingredient['uuid'] = $model->uuid;
                            } else {
                                $model = (new $className)->where('uuid', $ingredient['uuid'])->first();
                            }
                            // forces fetch of regional name
                            if ($model) {
                                $ingredient['name'] = $model->name;
                            }
                        }

                        if (isset($ingredient['quantity'])) {
                            $ingredient['quantity'] = floatval($ingredient['quantity']);
                        }

                        unset($ingredient['id']);

                        return $ingredient;
                    })->all() :
                    []
                :
                [];
        }


        if ($this->recipe) {
            $data['categories'] = $this->recipe ?
                $this->recipe->categories->map(function ($category) {
                    return [
                        'uuid' => $category->uuid,
                        'name' => $category->name,
                    ];
                })->all()
                :
                [];
        } else {
            if (isset($this->content['recipe_category_id'])) {
                $categories = collect();
                if (isUuid($this['content']['recipe_category_id'])) {
                    $categories = Category::where('uuid', $this['content']['recipe_category_id'])->get();
                } else {
                    $category = Category::find($this['content']['recipe_category_id']);
                    if ($category) {
                        $categories->push($category);
                    }
                }

                if ($categories->isNotEmpty()) {
                    $data['categories'] = $categories->map(function ($b) {
                        return [
                            'uuid' => $b->uuid,
                            'name' => $b->name,
                        ];
                    })->all();
                }
            }
        }

        return array_filter($data);
    }
}
