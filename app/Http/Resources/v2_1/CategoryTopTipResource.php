<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CategoryTopTipResource2.1",
 *     description="Individual category top tip response",
 *     type="object",
 *     title="Category Top Tip Resource V2.1",
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="Readable resource name"
 *     ),
 *     @OA\Property(
 *          property="body",
 *          type="string",
 *          description="Category top tip description"
 *     ),
 *)
 */
class CategoryTopTipResource extends JsonResource
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
            'type' => 'CategoryTopTip',
            'body' => $this->body,
        ];
    }
}
