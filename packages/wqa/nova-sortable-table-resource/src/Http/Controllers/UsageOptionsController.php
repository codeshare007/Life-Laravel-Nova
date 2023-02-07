<?php

namespace Wqa\NovaSortableTableResource\Http\Controllers;

use App\EffectiveSolutionUsage;
use App\Http\Controllers\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class UsageOptionsController extends Controller
{
    public function handle(NovaRequest $request)
    {
        $usages = EffectiveSolutionUsage::all()->pluck('description', 'id')->mapToOptions()->toArray();

        if (!$usages) {
            return response('', 200);
        }
        array_unshift($usages, ['label' => '---', 'value' => 0]);
        return response()->json(['usages' => $usages]);
    }
}
