<?php

namespace App\Http\Controllers\Api\v2_1\Favourite;

use App\Element;
use App\Favourite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidApiParameterException;

class FavouriteController extends Controller
{
    /**
     * @var Element
     */
    private $element;

    /**
     * @var Favourite
     */
    private $favourite;

    /**
     * FavouriteController constructor.
     * @param Element $element
     * @param Favourite $favourite
     */
    public function __construct(
        Element $element,
        Favourite $favourite
    ) {
        $this->element = $element;
        $this->favourite = $favourite;
    }

    /**
     * All user to post a toggle for favouriting
     * a model. If true, attach the polymorphic
     * relationship; if false, remove the favourite
     * model altogether
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\Resource
     * @throws InvalidApiParameterException
     */
    public function toggleFavourite(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'uuid' => 'required',
            'favourite' => 'required|boolean'
        ]);

        if ($validation->fails()) {
            throw new InvalidApiParameterException($validation->getMessageBag()->getMessages());
        }

        $element = $this->element
            ->with('elementDetails.favourites')
            ->find($request->get('uuid'));

        $result = null;
        if (
            $request->get('favourite') &&
            $element->elementDetails->favourites->where('user_id', auth('api')->id())->isEmpty()
        ) {
            $result = $this->favourite->create([
                'favouriteable_type' => $element->element_type,
                'favouriteable_id' => $element->element_id,
                'user_id' => auth('api')->id(),
            ]);
        } elseif (! $request->get('favourite')) {
            // remove authenticated users favourite if exists
            $result = $this->favourite->where('favouriteable_type', $element->element_type)
                ->where('favouriteable_id', $element->element_id)
                ->where('user_id', auth('api')->id())
                ->delete();
        }

        return response()->json(['success' => $result, 'user' => auth('api')->id()]);
    }
}
