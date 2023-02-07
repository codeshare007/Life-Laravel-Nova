<?php

namespace App\Http\Controllers\Api\UserGeneratedContent;

use App\Mail\ContentCreated;
use Illuminate\Http\Request;
use App\UserGeneratedContent;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Enums\UserGeneratedContentStatus;
use App\Http\Requests\UserGeneratedContent\StoreRequest;
use App\Http\Requests\UserGeneratedContent\UpdateRequest;

class UserGeneratedContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $userGeneratedContentGroup = $request->user()->content()->orderBy('name')->get()->mapToGroups(function($item) {
            return [$item['type'] => $item];
        });

        $resourceGroup = [];
        foreach ($userGeneratedContentGroup as $group => $userGeneratedContent) {
            $resourceGroup[$group] = api_resource('UserGeneratedContentResource')->collection($userGeneratedContent);
        }

        return $resourceGroup;
    }

    public function show(UserGeneratedContent $userGeneratedContent)
    {
        return api_resource('UserGeneratedContentResource')->make($userGeneratedContent);
    }

    public function store(StoreRequest $request)
    {
        $userGeneratedContent = $request->user()->content()->create($request->validated());

        if ($userGeneratedContent && $userGeneratedContent->isPublic == 1) {
            Mail::to($request->user())->send(new ContentCreated($userGeneratedContent->load('user')));
        }

        // Auto Approve
        // if (App::environment(['local', 'staging']) && $userGeneratedContent->is_public === 1) {
        //     $userGeneratedContent->approve();
        // }

        return api_resource('UserGeneratedContentResource')->make($userGeneratedContent->fresh());
    }

    public function update(UpdateRequest $request, UserGeneratedContent $userGeneratedContent)
    {
        $this->authorize('update', $userGeneratedContent);

        $userGeneratedContent->update($request->validated());
        $userGeneratedContent->resubmit();

        return api_resource('UserGeneratedContentResource')->make($userGeneratedContent->fresh());
    }

    public function destroy(UserGeneratedContent $userGeneratedContent)
    {
        $this->authorize('delete', $userGeneratedContent);

        $userGeneratedContent->delete();

        if ($userGeneratedContent->publicModel) {
            $userGeneratedContent->publicModel->anonymise();
        }

        return response(null, 204);
    }
}
