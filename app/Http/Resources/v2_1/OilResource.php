<?php

namespace App\Http\Resources\v2_1;

use App\Http\Resources\v1_1\TagResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="OilResource2.1",
 *     description="Individual oil resource response",
 *     type="object",
 *     title="Oil Resource V2.1",
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="uuid",
 *          type="integer",
 *          description="Oil UUID"
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
 *          property="blends_with",
 *          type="array",
 *          description="A list of items oil blends with",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.1")
 *     ),
 *     @OA\Property(
 *          property="related_blends",
 *          type="array",
 *          description="A list of blends the oil is found in",
 *          @OA\Items(ref="#/components/schemas/BasicResource2.1")
 *     ),
 *     @OA\Property(
 *          property="found_in",
 *          type="array",
 *          description="A list of oil solutions that the oil is found in",
 *          @OA\Items(ref="#/components/schemas/OilSolutionResource2.1")
 *     ),
 *     @OA\Property(
 *          property="properties",
 *          type="array",
 *          description="A list of associated properties",
 *          @OA\Items(ref="#/components/schemas/OilPropertyResource2.1")
 *     ),
 *     @OA\Property(
 *          property="main_constituents",
 *          type="array",
 *          description="A list of the oils main constituents",
 *          @OA\Items(ref="#/components/schemas/OilMainConstituentsResource2.1")
 *     ),
 *     @OA\Property(
 *          property="how_its_made",
 *          type="array",
 *          description="A list of the oils sourcing methods",
 *          @OA\Items(ref="#/components/schemas/OilHowItsMadeResource2.1")
 *     ),
 *     @OA\Property(
 *          property="usages",
 *          type="array",
 *          description="A list of the oils top uses",
 *          @OA\Items(ref="#/components/schemas/UsageResource2.1")
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
    public function toArray($request) : array
    {
        $resource = [
            'type' => $this->getApiModelName(),
            'uuid' => $this->uuid,
            'name' => $this->name,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : '',
            'color' => $this->color,
            'is_featured' => $this->is_featured,
        ];

        if (showFullDetails($request)) {
            $resource = array_merge($resource, [
                'id' => $this->id,
                'latin_name' => $this->latin_name,
                'emotions' => [
                    $this->emotion_1,
                    $this->emotion_2,
                    $this->emotion_3,
                ],
                'emotion_from' => $this->emotion_from,
                'emotion_to' => $this->emotion_to,
                'fact' => $this->fact,
                'research' => $this->research,
                'views_count' => 0,
                'comments_count' => 0,
                'recent_views_count' => 0,
                'safety_information' => $this->whenLoaded('safetyInformation') ?
                    $this->safetyInformation->description :
                    null,
                'blends_with' => BasicResource::collection($this->whenLoaded('blendsWith')),
                'related_blends' => BasicResource::collection($this->whenLoaded('foundInBlends')),
                'found_in' => OilSolutionResource::collection($this->whenLoaded('foundIn')),
                'properties' => OilPropertyResource::collection($this->whenLoaded('properties')),
                'main_constituents' => OilMainConstituentsResource::collection($this->whenLoaded('constituents')),
                'how_its_made' => OilHowItsMadeResource::collection($this->whenLoaded('sourcingMethods')),
                'usages' => UsageResource::collection($this->whenLoaded('usages'))->unique(),
            ]);
        }
        return array_filter($resource);
    }
}
