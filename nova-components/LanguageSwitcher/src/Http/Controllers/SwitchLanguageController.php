<?php

namespace Wqa\LanguageSwitcher\Http\Controllers;

use App\Enums\UserLanguage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SwitchLanguageController extends Controller
{
    public function __invoke(Request $request)
    {
        session()->put('selected_language', UserLanguage::getInstance($request->language));
    }
}
