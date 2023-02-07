<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Juampi92\APIResources\Facades\APIResource;
use App\Exceptions\InvalidApiParameterException;

class DeltaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/{lang}/v2.1/delta",
     *     tags={"V2.1-api-token"},
     *     @OA\Parameter(
     *         name="lang",
     *         in="path",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             default="en",
     *             type="string",
     *             enum={"en", "sp", "jp"},
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                  format="application/json",
     *                  property="data",
     *                  @OA\JsonContent(
     *                      type="object",
     *                  )
     *             )
     *         ),
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="error"),
     * )
     */
    public function index(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'modified_since' => 'date_format:d-m-Y H:i:s',
        ]);

        if ($validation->fails()) {
            throw new InvalidApiParameterException($validation->getMessageBag()->getMessages());
        }

        $userLastUpdatedFromDeltaAt = $request->modified_since ? Carbon::createFromFormat('d-m-Y H:i:s', $request->modified_since) : Carbon::createFromTimestamp(0);
        $modifiedSince = ($this->deltaInvalidatedAt() > $userLastUpdatedFromDeltaAt) ? Carbon::createFromTimestamp(0) : $userLastUpdatedFromDeltaAt;

        $dataProviderClassName = __NAMESPACE__ . '\\v' . APIResource::getVersion() . '\DeltaData';

        if (class_exists($dataProviderClassName)) {
            return (new $dataProviderClassName($modifiedSince))->getData();
        }

        return response(null, 404);
    }

    /**
     * Return the date and time that the whole delta was invalidated at
     *
     * @return Carbon
     */
    protected function deltaInvalidatedAt(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',
            \Setting::get('delta_invalidated_at', Carbon::createFromTimestamp(0))
        );
    }
}
