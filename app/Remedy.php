<?php

namespace App;

use App\Enums\ApiVersion;
use Illuminate\Support\Arr;
use App\Traits\ImageableTrait;
use App\Traits\ModelNameTrait;
use App\Traits\ElementApiTrait;
use App\Traits\CollectableTrait;
use App\Traits\FavouriteableTrait;
use App\Traits\IngredientableTrait;
use App\Traits\UserGenerateableTrait;
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;
use App\Traits\InvalidatesDeltaWhenDeletingTrait;

class Remedy extends Model implements Sortable
{
    use FavouriteableTrait;
    use ImageableTrait;
    use CollectableTrait;
    use SortableTrait;
    use InvalidatesDeltaWhenDeletingTrait;
    use IngredientableTrait;
    use UserGenerateableTrait;
    use ElementApiTrait;
    use ModelNameTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    protected $fillable = [
        'uuid',
        'name',
        'image_url',
        'color',
        'short_description',
        'body',
        'user_id',
    ];

    protected $touches = ['ailment'];

    public static function getDefaultIncludes(ApiVersion $version): array
    {
        if ($version->is(ApiVersion::v2_1)) {
            return [
                'bodySystems',
                'relatedRemedies',
                'remedyIngredients.ingredientable',
                'ailment',
            ];
        }

        if ($version->is(ApiVersion::v3_0)) {
            return [
                'bodySystems',
                'relatedRemedies',
                'remedyIngredients',
                'ailment',
            ];
        }

        return [];
    }

    public function remedyIngredients()
    {
        return $this->hasMany(RemedyIngredient::class);
    }

    public function bodySystems()
    {
        return $this->belongsToMany(BodySystem::class);
    }

    public function ailment()
    {
        return $this->belongsTo(Ailment::class);
    }

    public function relatedRemedies()
    {
        return $this->belongsToMany(Remedy::class, 'remedy_related_remedy', 'remedy_id', 'related_remedy_id');
    }

    public function approveUserContent(UserGeneratedContent $userGeneratedContent)
    {
        $this->name = $userGeneratedContent->name;
        $this->body = $userGeneratedContent->content['instructions'] ?? '---';
        $this->user_id = $userGeneratedContent->user_id ?? null;
        $this->image_url = $userGeneratedContent->image_url;

        if ($this->save()) {
            $this->addIngredients($userGeneratedContent->content['ingredients'], $this->remedyIngredients());

            // Relate the selected BodySystem to the Remedy
            if (Arr::has($userGeneratedContent->content, 'body_system_id')) {
                $bodySystem = BodySystem::findByIdOrUuid($userGeneratedContent->content['body_system_id']);
                if ($bodySystem) {
                    $this->bodySystems()->attach($bodySystem);
                }
            }

            // Relate the selected Ailment to the Remedy
            if (Arr::has($userGeneratedContent->content, 'ailment_id')) {
                $ailment = Ailment::findByIdOrUuid($userGeneratedContent->content['ailment_id']);
                if ($ailment) {
                    $this->ailment()->associate($ailment);
                    $this->save();
                }
            }

            // Relate the selected Ailment to the selected BodySystem if it's not already related
            if (Arr::has($userGeneratedContent->content, ['body_system_id', 'ailment_id'])) {
                $bodySystem = BodySystem::findByIdOrUuid($userGeneratedContent->content['body_system_id']);
                $ailment = Ailment::findByIdOrUuid($userGeneratedContent->content['ailment_id']);

                if ($bodySystem && $ailment) {
                    $bodySystem->ailments()->syncWithoutDetaching([$ailment->id]);
                    $bodySystem->touch();
                }
            }

            return $this->id;
        }

        return false;
    }
}
