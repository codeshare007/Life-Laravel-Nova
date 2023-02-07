<?php

namespace App\Http\Controllers\Api\ContentSuggestion;

use App\ContentSuggestion;
use App\Mail\ContentSuggested;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Enums\ContentSuggestionAssociationType;
use App\Http\Requests\ContentSuggestion\StoreRequest;

class ContentSuggestionController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth:api');
    }

    public function store(StoreRequest $request)
    {
        $contentSuggestion = ContentSuggestion::create([
            'name' => $request->name,
            'type' => $request->type,
            'mode' => $request->mode,
            'content' => $request->content,
            'association_type' => $request->association_type ? ContentSuggestionAssociationType::getValue($request->association_type) : null,
            'association_id' => $request->association_id ?? null,
            'user_id' => Auth::id(),
        ]);

        if ($contentSuggestion) {
            Mail::to(Auth::user())->send(new ContentSuggested($contentSuggestion->load('user')));
        }

        return response(null, 201);
    }
}
