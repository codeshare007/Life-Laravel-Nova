<?php

namespace App\Http\Resources\v3_0;

/**
 * @OA\Schema(
 *     schema="QuestionResource3.0",
 *     description="Individual user response",
 *     type="object",
 *     title="Question Resource V3.0",
 *     @OA\Property(
 *          property="uuid",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="category",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="title",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="description",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="user_id",
 *          type="integer"
 *     ),
 *     @OA\Property(
 *          property="firebase_document",
 *          type="string"
 *     )
 *)
 */
class QuestionResource extends BaseResource
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
            'uuid' => $this->uuid,
            'category' => $this->category,
            'title' => $this->title,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'firebase_document' => $this->firebase_document,
        ];
    }
}
