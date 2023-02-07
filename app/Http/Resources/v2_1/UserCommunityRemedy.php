<?php

namespace App\Http\Resources\v2_1;

use App\Ailment;
use App\BodySystem;
use App\Enums\UserGeneratedContentStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="UserCommunityRemdy2.1",
 *     description="Individual basic resource response",
 *     type="object",
 *     title="User Community Remdy V2.1",
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
class UserCommunityRemedy extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'uuid' => $this->uuid,
            'type' => 'Remedy',
            'name' => $this->name,
            'status' => $this->is_public ? UserGeneratedContentStatus::getKey($this->status) : UserGeneratedContentStatus::getKey(0),
            'public_uuid' => $this->remedy ? $this->remedy->uuid : null,
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

        if ($this->remedy) {
            $data['body_systems'] = $this->remedy ?
                $this->remedy->bodySystems->map(function ($bodySystems) {
                    return [
                        'uuid' => $bodySystems->uuid,
                        'name' => $bodySystems->name,
                    ];
                })->all() :
                [];
            $data['ailments'] = $this->remedy ?
                $this->remedy->ailment ?
                    [[
                        'uuid' => $this->remedy->ailment->uuid,
                        'name' => $this->remedy->ailment->name,
                    ]] :
                    []
                :
                [];
        } else {
            if (isset($this->content['body_system_id'])) {
                $bodySystems = collect();
                if (isUuid($this['content']['body_system_id'])) {
                   $bodySystems = BodySystem::where('uuid', $this['content']['body_system_id'])->get();
                } else {
                    $bodySystem = BodySystem::find($this['content']['body_system_id']);
                    if ($bodySystem) {
                        $bodySystems->push($bodySystem);
                    }
                }

                if ($bodySystems->isNotEmpty()) {
                    $data['body_systems'] = $bodySystems->map(function ($b) {
                        return [
                            'uuid' => $b->uuid,
                            'name' => $b->name,
                        ];
                    })->all();
                }
            }

            if (isset($this->content['ailment_id'])) {
                $ailments = collect();
                if (isUuid($this['content']['ailment_id'])) {
                    $ailments = Ailment::where('uuid', $this['content']['ailment_id'])->get();
                } else {
                    $ailment = Ailment::find($this['content']['ailment_id']);
                    if ($ailment) {
                        $ailments->push($ailment);
                    }
                }

                if ($ailments->isNotEmpty()) {
                    $data['ailments'] = $ailments->map(function ($b) {
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
