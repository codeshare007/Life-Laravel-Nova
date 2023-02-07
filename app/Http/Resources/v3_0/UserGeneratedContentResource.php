<?php

namespace App\Http\Resources\v3_0;

use App\Enums\UserGeneratedContentStatus;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="UserGeneratedContentResource3.0",
 *     description="Individual ailment remedy resource response",
 *     type="object",
 *     title="User Generated Content Resource V3.0",
 *     @OA\Property(
 *          property="uuid",
 *          type="integer",
 *          description="Resource UUID"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer"
 *     ),
 *     @OA\Property(
 *          property="public_uuid",
 *          type="integer"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Resource name"
 *     ),
 *     @OA\Property(
 *          property="image_url",
 *          type="string",
 *          description="Resource image url"
 *     ),
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="status",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="is_public",
 *          type="boolean"
 *     ),
 *     @OA\Property(
 *          property="content",
 *          type="array",
 *          @OA\Items()
 *     ),
 *     @OA\Property(
 *          property="rejection_reason_subject",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="rejection_reason_description",
 *          type="string"
 *     ),
 * )
 */
class UserGeneratedContentResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_filter([
            'uuid' => $this->uuid,
            'id' => $this->id,
            'public_uuid' => $this->publicModel->uuid ?? null,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'type' => $this->type,
            'status' => $this->is_public ? UserGeneratedContentStatus::getKey($this->status) : UserGeneratedContentStatus::Private()->key,
            'is_public' => $this->is_public,
            'content' => [
                'ailment_id' => $this->ailmentUuid(),
                'body_system_id' => $this->bodySystemUuid(),
                'recipe_category_id' => $this->recipeCategoryUuid(),
                'instructions' => isset($this->content['instructions']) ? $this->content['instructions'] : null,
                'ingredients' => $this->ingredients(),
            ],
            'rejection_reason_subject' => $this->rejection_reason_subject,
            'rejection_reason_description' => $this->rejection_reason_description,
        ]);
    }

    protected function ingredients(): array
    {
        if (!isset($this->content['ingredients']) || !is_array($this->content['ingredients'])) {
            return [];
        }

        return collect($this->content['ingredients'])->map(function ($ingredient) {
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
        })->all();
    }
}
