<?php

namespace App\Http\Controllers\Api\v3_0\Favourite;

use App\Element;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidApiParameterException;

class ToggleFavouriteController extends Controller
{
    /**
     * @OA\Post(
     *     path="/{lang}/v3.0/favourite",
     *     tags={"V3.0-api-auth"},
     *     @OA\Parameter(
     *         name="lang",
     *         in="path",
     *         description="language",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             default="en",
     *             type="string",
     *             enum={"en", "sp", "jp"},
     *         )
     *     ),
     *     operationId="toggle",
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         description="Input data format",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  required={"uuid", "favourite"},
     *                  @OA\Property(
     *                      property="uuid",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="favourite",
     *                      type="boolean"
     *                  ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              type="object",
     *          ),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     * )
     */
    public function toggle(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'uuid' => 'required',
            'favourite' => 'required|boolean'
        ]);

        if ($validation->fails()) {
            throw new InvalidApiParameterException($validation->getMessageBag()->getMessages());
        }

        $element = Element::findOrFail($request->uuid);
        $model = $element->elementDetails;

        return response()->json([
            'success' => $request->favourite ? $this->favourite($model) : $this->unfavourite($model),
            'user' => $request->user()->id
        ]);
    }

    protected function favourite(Model $model): bool
    {
        if (! $model->favourites->where('user_id', request()->user()->id)->isEmpty()) {
            return false;            
        }

        return $model->favourites()->create([
            'user_id' => request()->user()->id,
        ])->wasRecentlyCreated;
    }

    protected function unfavourite(Model $model): bool
    {
        return $model->favourites()->where('user_id', request()->user()->id)->delete();
    }
}
