<?php

namespace App\Http\Controllers\Api\v2_1\Region;

use App\Enums\Region;
use App\Http\Controllers\Controller;

class RegionController extends Controller
{
    /**
     * @param $lang
     * @return array
     */
    public function index($lang)
    {
        return api_resource('RegionResourceCollection')->make(collect(Region::getInstances()));
    }
}
