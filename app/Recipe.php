<?php

namespace App;

use App\Enums\ApiVersion;
use Illuminate\Support\Arr;
use App\Traits\ImageableTrait;
use App\Traits\ModelNameTrait;
use App\Traits\ElementApiTrait;
use App\Traits\CollectableTrait;
use App\Traits\FeatureableTrait;
use App\Traits\FavouriteableTrait;
use App\Traits\IngredientableTrait;
use App\Traits\UserGenerateableTrait;
use Illuminate\Database\Eloquent\Model;
use App\Traits\InvalidatesDeltaWhenDeletingTrait;

class Recipe extends Model
{
    use FavouriteableTrait;
    use FeatureableTrait;
    use ImageableTrait;
    use CollectableTrait;
    use InvalidatesDeltaWhenDeletingTrait;
    use IngredientableTrait;
    use UserGenerateableTrait;
    use ElementApiTrait;
    use ModelNameTrait;

    protected $fillable = [
        'uuid',
        'name',
        'image_url',
        'color',
        'short_description',
        'body',
        'is_featured',
        'user_id',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public static function getDefaultIncludes(ApiVersion $version): array
    {
        if ($version->is(ApiVersion::v2_1)) {
            return [
                'recipeIngredients.ingredientable',
                'relatedRecipes',
                'categories'
            ];
        }

        if ($version->is(ApiVersion::v3_0)) {
            return [
                'recipeIngredients',
                'relatedRecipes',
                'categories',
            ];
        }

        return [];
    }

    public function recipeIngredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function relatedRecipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_related_recipe', 'recipe_id', 'related_recipe_id');
    }

    public function approveUserContent(UserGeneratedContent $userGeneratedContent)
    {
        $this->name = $userGeneratedContent->name;
        $this->body = $userGeneratedContent->content['instructions'] ?? '---';
        $this->user_id = $userGeneratedContent->user_id ?? null;
        $this->image_url = $userGeneratedContent->image_url;

        if ($this->save()) {
            $this->addIngredients($userGeneratedContent->content['ingredients'], $this->recipeIngredients());

            if (Arr::has($userGeneratedContent->content, 'recipe_category_id')) {
                $recipeCategory = Category::findByIdOrUuid($userGeneratedContent->content['recipe_category_id']);

                if ($recipeCategory) {
                    $this->categories()->attach($recipeCategory);
                    $recipeCategory->touch();
                }
            }

            return $this->id;
        }

        return false;
    }
}
