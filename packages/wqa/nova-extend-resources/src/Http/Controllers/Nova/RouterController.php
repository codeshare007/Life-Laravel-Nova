<?php

namespace Wqa\NovaExtendResources\Http\Controllers\Nova;

use Illuminate\Routing\Controller;

class RouterController extends Controller
{
    /**
     * Display the Nova Vue router.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('nova::router');
    }
}
