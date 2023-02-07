<?php

namespace Wqa\LanguageSwitcher\Http\Controllers;

use App\Enums\UserLanguage;
use App\Http\Controllers\Controller;

class InitController extends Controller
{
    public function __invoke()
    {
        $languages = collect(UserLanguage::getInstances())->map(function (UserLanguage $language) {
            return [
                'label' => $language->key,
                'value' => $language->value,
            ];
        })->values()->toArray();

        $language = session()->get('selected_language', UserLanguage::English())->value;

        return response()->json([
            'language' => $language,
            'languages' => $languages,
        ]);
    }
}
