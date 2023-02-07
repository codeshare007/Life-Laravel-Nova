<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserLanguage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StatusRequest;
use App\Services\GlobalStatus\GlobalStatus;

/**
 * @OA\Get(
 *     path="/{lang}/v2.1/status",
 *     description="Fetch all counts",
 *     tags={"V2.1-api-token"},
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
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string"),
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="button_text", type="string"),
 *             @OA\Property(property="button_url", type="string"),
 *             @OA\Property(property="updated_at", type="string"),
 *         ),
 *     ),
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=401, description="Unauthorized"),
 * )
 */
/**
 * @OA\Get(
 *     path="/{lang}/v3.0/status",
 *     description="Fetch all counts",
 *     tags={"V3.0-api-token"},
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
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string"),
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="button_text", type="string"),
 *             @OA\Property(property="button_url", type="string"),
 *             @OA\Property(property="updated_at", type="string"),
 *         ),
 *     ),
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=401, description="Unauthorized"),
 * )
 */
class StatusController extends Controller
{
    public function __invoke(StatusRequest $request)
    {
        $language = UserLanguage::coerce($request->lang) ?? UserLanguage::English();

        return (new GlobalStatus)->for($request->platform, $request->app_version, $language);
    }
}
