<?php

namespace App\Http\Controllers\Api\v3_0;

use App\Element;
use App\Enums\ElementType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ElementController extends Controller
{
    /**
     * @param Request $request
     * @param $lang
     * @param $uuid
     * @return APIResource
     */
    public function show(Request $request, $lang, Element $element)
    {
        $elementType = ElementType::getInstance($element->element_type);

        if ($elementType->in([ElementType::UserGeneratedContent])) {
            return response()->json(['UGC unavailable from this endpoint.'], 422);
        }

        return api_resource('ElementResource')->make($element);
    }
}
