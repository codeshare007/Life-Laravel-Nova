<?php

namespace App\Http\Controllers\Api\v3_0;

use App\Http\Controllers\Controller;
use App\Http\Requests\DownloadAppJsonRequest;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Get(
 *     path="/{lang}/v3.0/download-app-json",
 *     description="Download App.",
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
 *     ),
 *     security={{ "bearerAuth": {} }},
*     @OA\Response(response=404, description="The JSON file doesn't exist"),
 *     @OA\Response(response=401, description="Unauthorized"),
 * )
 */
class DownloadAppJsonController extends Controller
{
    public function __invoke(DownloadAppJsonRequest $request)
    {
        $language = $request->language;
        $filePath = Storage::disk('local')->path("app-json/$language.json");

        abort_unless(file_exists($filePath), 404, "The JSON file doesn't exist");

        return response()->download($filePath);
    }
}
