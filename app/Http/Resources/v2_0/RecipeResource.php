<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="RecipeResource2.0",
 *     description="Individual recipe response",
 *     type="object",
 *     title="Recipe Resource V2.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Recipe Id"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Recipe name"
 *     ),
 *     @OA\Property(
 *          property="image_url",
 *          type="string",
 *          description="Recipe image url"
 *     ),
 *     @OA\Property(
 *          property="color",
 *          type="string",
 *          description="Recipe color"
 *     ),
 *     @OA\Property(
 *          property="is_featured",
 *          type="boolean",
 *          description="Flag if recipe is featured"
 *     ),
 *     @OA\Property(
 *          property="body",
 *          type="string",
 *          description="Recipe body"
 *     ),
 *     @OA\Property(
 *          property="views_count",
 *          type="integer",
 *          description="Total view count on recipe"
 *     ),
 *     @OA\Property(
 *          property="comments_count",
 *          type="integer",
 *          description="Total number of comments for the recipe"
 *     ),
 *     @OA\Property(
 *          property="categories",
 *          type="array",
 *          description="A list of associated categories",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="related_recipes",
 *          type="array",
 *          description="A list of related recipes",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="ingredients",
 *          type="array",
 *          description="A list of associated ingredients",
 *          @OA\Items(ref="#/components/schemas/RecipeIngredientResource2.0")
 *     ),
 *     @OA\Property(
 *          property="is_user_generated",
 *          type="boolean",
 *          description="Flag if recipe is user generated"
 *     ),
 *     @OA\Property(
 *          property="user_id",
 *          type="integer",
 *          description="User id who created the recipe"
 *     ),
 *)
 */
class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'resource_type' => 'Recipe',
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'is_featured' => (int)$this->is_featured,
            'body' => $this->body,
            'views_count' => 0,
            'comments_count' => 0,
            'categories' => BasicResource::collection($this->whenLoaded('categories')),
            'related_recipes' => BasicResource::collection($this->whenLoaded('relatedRecipes')),
            'ingredients' => RecipeIngredientResource::collection($this->whenLoaded('recipeIngredients')),
            'is_user_generated' => $this->is_user_generated,
            'user_id' => $this->user_id,
        ];
    }
}
