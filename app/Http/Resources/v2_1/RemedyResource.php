<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="RemedyResource2.1",
 *     description="Individual remedy response",
 *     type="object",
 *     title="Remedy Resource V2.1",
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="uuid",
 *          type="string",
 *          description="Remedy UUID"
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
 *          @OA\Items(ref="#/components/schemas/RemedyBodySystemResource2.1")
 *     ),
 *     @OA\Property(
 *          property="ingredients",
 *          type="array",
 *          description="A list of associated ingredients",
 *          @OA\Items(ref="#/components/schemas/RemedyIngredientResource2.1")
 *     ),
 *     @OA\Property(
 *          property="ailments",
 *          type="array",
 *          description="A list of associated ailments",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.1")
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
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = [
            'type' => $this->getApiModelName(),
            'uuid' => $this->uuid,
            'name' => $this->name,
            'body_systems' => $this->whenLoaded('bodySystems') ? RemedyBodySystemResource::collection($this->whenLoaded('bodySystems')) : null,
            'ailments' => $this->whenLoaded('ailment') ? [RemedyAilmentResource::make($this->whenLoaded('ailment'))] : null,
            'user_id' => $this->user_id,
        ];

        if (showFullDetails($request)) {
            $resource = array_merge($resource, [
                'id' => $this->id,
                'ingredients' => RemedyIngredientResource::collection($this->whenLoaded('remedyIngredients')),
                'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
                'body' => $this->body,
                'views_count' => 0,
                'comments_count' => 0,
            ]);
        }
        
        return array_filter($resource);
    }
}
