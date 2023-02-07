<?php

namespace App\Http\Resources\v2_1;

use App\Ailment;
use App\BodySystem;
use App\Category;
use App\Enums\UserGeneratedContentStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserGeneratedContentEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $ailmentId = isset($this->content['ailment_id']) ? $this->content['ailment_id'] : null;
        $bodySystemId = isset($this->content['body_system_id']) ? $this->content['body_system_id'] : null;
        $categoryId = isset($this->content['recipe_category_id']) ? $this->content['recipe_category_id'] : null;
        if (!isUuid($ailmentId) && isset($this->content['ailment_id'])) {
            $ailment = Ailment::find($this->content['ailment_id']);
            $ailmentId = null;
            if ($ailment) {
                $ailmentId = $ailment->uuid;
            }
        }

        if (!isUuid($bodySystemId) && isset($this->content['body_system_id'])) {
            $bodySystem = BodySystem::find($this->content['body_system_id']);
            $bodySystemId = null;
            if ($bodySystem) {
                $bodySystemId = $bodySystem->uuid;
            }
        }

        if (!isUuid($categoryId) && isset($this->content['recipe_category_id'])) {
            $category = Category::find($this->content['recipe_category_id']);
            $categoryId = null;
            if ($category) {
                $categoryId = $category->uuid;
            }
        }

        $resourceArr = array_filter([
            'uuid' => $this->uuid,
            'id' => $this->id,
            'public_id' => $this->association_id,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'type' => $this->type,
            'status' => $this->is_public ? UserGeneratedContentStatus::getKey($this->status) : UserGeneratedContentStatus::getKey(0),
            'is_public' => $this->is_public,
            'content' => [
                'ailment_id' => $ailmentId,
                'body_system_id' => $bodySystemId,
                'recipe_category_id' => $categoryId,
                'instructions' => isset($this->content['instructions']) ? $this->content['instructions'] : null,
                'ingredients' => isset($this->content['ingredients']) ?
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
                    []
            ],
            'rejection_reason_subject' => $this->rejection_reason_subject,
            'rejection_reason_description' => $this->rejection_reason_description,
        ]);

        return $resourceArr;
    }
}
