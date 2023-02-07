<?php

namespace App\Http\Controllers\Api\v3_0;

use App\Http\Controllers\Controller;
use App\Http\Requests\DownloadRealmDatabaseRequest;

/**
 * @OA\Get(
 *     path="/{lang}/v3.0/download-database",
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
 *     description="Download database.",
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *     ),
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=404, description="A realm database for the requested language doesn't exist"),
 *     @OA\Response(response=401, description="Unauthorized"),
 * )
 */
class DownloadRealmDatabaseController extends Controller
{
    public function __invoke(DownloadRealmDatabaseRequest $request)
    {
        $env = app()->environment();
        $language = $request->language;
        $filePath = resource_path("/realm-databases/$env/$language.zip");

        abort_unless(file_exists($filePath), 404, "A realm database for the requested language ($request->language) doesn't exist");

        return response()->download($filePath);
    }
}
