<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Card;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cards = Card::active()->ordered()->paginate(10);

        return api_resource('CardResource')->collection($cards);
    }
}
