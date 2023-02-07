<?php

namespace App\Services\SeedData;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommunityAuthorsSeedData extends BaseSeedData
{
    public function build(): BaseSeedData
    {
        // Users who have content that is public and approved
        $authors = User::whereHas('content', function (Builder $query) {
            $query->whereHasPublicModel();
        })->with([
            'content' => function(HasMany $query) {
                $query->whereHasPublicModel()->with([
                    'recipe',
                    'remedy',
                ]);
            },
        ])->get();

        $this->seedData = api_resource('UserResource')->collection($authors);

        return $this;
    }
}
