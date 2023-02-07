<?php

namespace Wqa\SeedDownload\Http\Controllers;

use App\Enums\UserLanguage;
use App\Http\Controllers\Controller;

class LanguagesController extends Controller
{
    public function __invoke()
    {
        return collect(UserLanguage::getInstances())->map(function (UserLanguage $language) {
            return [
                'label' => $language->key,
                'value' => $language->value,
            ];
        })->values()->toArray();
    }
}
