<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="RemedyResource2.0",
 *     description="Individual remedy response",
 *     type="object",
 *     title="Remedy Resource V2.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Remedy Id"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Remedy name"
 *     ),
 *     @OA\Property(
 *          property="image_url",
 *          type="string",
 *          description="Remedy image url"
 *     ),
 *     @OA\Property(
 *          property="color",
 *          type="string",
 *          description="Remedy color"
 *     ),
 *     @OA\Property(
 *          property="is_featured",
 *          type="boolean",
 *          description="Flag if remedy is featured"
 *     ),
 *     @OA\Property(
 *          property="method",
 *          type="string",
 *          description="Remedy body"
 *     ),
 *     @OA\Property(
 *          property="views_count",
 *          type="integer",
 *          description="Total view count on remedy"
 *     ),
 *     @OA\Property(
 *          property="comments_count",
 *          type="integer",
 *          description="Total number of comments for the remedy"
 *     ),
 *     @OA\Property(
 *          property="body_systems",
 *          type="array",
 *          description="A list of associated body systems",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="related_remedies",
 *          type="array",
 *          description="A list of related remedies",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="ingredients",
 *          type="array",
 *          description="A list of associated ingredients",
 *          @OA\Items(ref="#/components/schemas/RemedyIngredientResource2.0")
 *     ),
 *     @OA\Property(
 *          property="ailments",
 *          type="array",
 *          description="An associated ailment",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="is_user_generated",
 *          type="boolean",
 *          description="Flag if remedy is user generated"
 *     ),
 *     @OA\Property(
 *          property="user_id",
 *          type="integer",
 *          description="User id who created the remedy"
 *     ),
 *)
 */
class RemedyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'resource_type' => 'Remedy',
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'method' => $this->body,
            'views_count' => 0,
            'comments_count' => 0,
            'body_systems' => BasicResource::collection($this->whenLoaded('bodySystems')),
            'related_remedies' => BasicResource::collection($this->whenLoaded('relatedRemedies')),
            'ingredients' => RemedyIngredientResource::collection($this->whenLoaded('remedyIngredients')),
            'ailment' => BasicResource::make($this->whenLoaded('ailment')),
            'is_user_generated' => $this->is_user_generated,
            'user_id' => $this->user_id,
        ];
    }
}
