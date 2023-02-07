<?php

namespace App\Http\Resources\v2_0;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="OilResource2.0",
 *     description="Individual oil resource response",
 *     type="object",
 *     title="Oil Resource V2.0",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="Oil Id"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Oil name"
 *     ),
 *     @OA\Property(
 *          property="image_url",
 *          type="string",
 *          description="Oil image url"
 *     ),
 *     @OA\Property(
 *          property="color",
 *          type="string",
 *          description="Oil color"
 *     ),
 *      @OA\Property(
 *          property="emotions",
 *          type="array",
 *          description="Top three emotions",
 *          @OA\Items(type="string")
 *     ),
 *     @OA\Property(
 *          property="emotion_from",
 *          type="string",
 *          description="Oil emotion from"
 *     ),
 *     @OA\Property(
 *          property="emotion_to",
 *          type="string",
 *          description="Oil emotion to"
 *     ),
 *     @OA\Property(
 *          property="safety_information",
 *          type="string",
 *          description="Oil safety information"
 *     ),
 *     @OA\Property(
 *          property="fact",
 *          type="string",
 *          description="Oil fact"
 *     ),
 *     @OA\Property(
 *          property="is_featured",
 *          type="boolean",
 *          description="Flag if oil is featured"
 *     ),
 *     @OA\Property(
 *          property="research",
 *          type="string",
 *          description="Oil research"
 *     ),
 *     @OA\Property(
 *          property="views_count",
 *          type="integer",
 *          description="Total view count on oil"
 *     ),
 *     @OA\Property(
 *          property="comments_count",
 *          type="integer",
 *          description="Total number of comments for the oil"
 *     ),
 *     @OA\Property(
 *          property="related_recipes",
 *          type="array",
 *          description="A list of associated recipes",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="blends_with",
 *          type="array",
 *          description="A list of items oil blends with",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="related_blends",
 *          type="array",
 *          description="A list of blends the oil is found in",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="found_in",
 *          type="array",
 *          description="A list of oil solutions that the oil is found in",
 *          @OA\Items(ref="#/components/schemas/OilSolutionResource2.0")
 *     ),
 *     @OA\Property(
 *          property="top_properties",
 *          type="array",
 *          description="A list of associated properties",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="main_constituents",
 *          type="array",
 *          description="A list of the oils main constituents",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="how_its_made",
 *          type="array",
 *          description="A list of the oils sourcing methods",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.0")
 *     ),
 *     @OA\Property(
 *          property="top_uses",
 *          type="array",
 *          description="A list of the oils top uses",
 *          @OA\Items(ref="#/components/schemas/UsageResource2.0")
 *     ),
 *)
 */
class OilResource extends JsonResource
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
            'resource_type' => 'Oil',
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'latin_name' => $this->latin_name,
            'emotions' => [
                $this->emotion_1,
                $this->emotion_2,
                $this->emotion_3,
            ],
            'emotion_from' => $this->emotion_from,
            'emotion_to' => $this->emotion_to,
            'safety_information' => $this->when($this->relationLoaded('safetyInformation'), $this->safetyInformation->description ?? null),
            'fact' => $this->fact,
            'is_featured' => (int)$this->is_featured,
            'research' => $this->research,
            'views_count' => 0,
            'comments_count' => 0,
            'related_recipes' => BasicResource::collection($this->whenLoaded('relatedRecipes')),
            'blends_with' => BasicResource::collection($this->whenLoaded('blendsWith')),
            'related_blends' => BasicResource::collection($this->whenLoaded('foundInBlends')),
            'found_in' => OilSolutionResource::collection($this->whenLoaded('foundIn')),
            'top_properties' => BasicResource::collection($this->whenLoaded('properties')),
            'main_constituents' => BasicResource::collection($this->whenLoaded('constituents')),
            'how_its_made' => BasicResource::collection($this->whenLoaded('sourcingMethods')),
            'top_uses' => UsageResource::collection($this->whenLoaded('usages')),
        ];
    }
}
