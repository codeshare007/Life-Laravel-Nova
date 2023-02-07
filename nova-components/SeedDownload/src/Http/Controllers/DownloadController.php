<?php

namespace Wqa\SeedDownload\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Services\SeedData\ElementsSeedData;
use App\Services\SeedData\CommunityAuthorsSeedData;
use Wqa\SeedDownload\Http\Requests\DownloadSeedDataRequest;

class DownloadController extends Controller
{
    public function elements(DownloadSeedDataRequest $request)
    {
        $json = (new ElementsSeedData)
            ->setLanguage($request->language())
            ->build()
            ->toJson();

        return $this->downloadJson($json, 'elements');
    }

    public function communityAuthors(DownloadSeedDataRequest $request)
    {
        $json = (new CommunityAuthorsSeedData)
            ->setLanguage($request->language())
            ->build()
            ->toJson();

        return $this->downloadJson($json, 'community-authors');
    }

    protected function downloadJson($json, $filename): Response
    {
        $filename = $filename . '-' . date('Y-m-d-H-i-s') . '-' . app()->environment() . '-' . request()->language;

        return response($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.json"',
        ]);
    }
}
