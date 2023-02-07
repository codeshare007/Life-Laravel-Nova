<?php

namespace App\Http\Controllers\Api\Favourite;

use App\Favourite;
use Illuminate\Http\Request;
use BenSampo\Enum\Rules\EnumKey;
use App\Enums\FavouriteableModels;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidApiParameterException;

class FavouriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'favouriteable_type' => [
                'required',
                new EnumKey(FavouriteableModels::class),
            ],
            'favouriteable_id' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            throw new InvalidApiParameterException($validation->getMessageBag()->getMessages());
        }

        $favourite = Favourite::create([
           'favouriteable_type' => FavouriteableModels::getValue($request->favouriteable_type),
           'favouriteable_id' => $request->favouriteable_id,
           'user_id' => Auth::id(),
        ]);

        $favourite->load('favouriteable');

        return api_resource('FavouriteResource')->make($favourite);
    }

    public function destroy(Favourite $favourite)
    {
        $this->authorize('delete', $favourite);

        $favourite->delete();

        return response(null, 204);
    }
}
