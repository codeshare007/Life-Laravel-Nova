<?php

namespace App;

use App\Enums\ApiVersion;
use App\Traits\ImageableTrait;
use App\Traits\ElementApiTrait;
use Illuminate\Database\Eloquent\Model;
use App\Enums\UserGeneratedContentStatus;
use Illuminate\Database\Eloquent\Builder;
use App\Events\ApprovedUserGeneratedContent;
use App\Events\RejectedUserGeneratedContent;

class UserGeneratedContent extends Model
{
    use ImageableTrait;
    use ElementApiTrait;

    protected $table = 'users_generated_content';

    protected $fillable = [
        'uuid',
        'user_id',
        'image_url',
        'name',
        'type',
        'association_id',
        'content',
        'status',
        'is_public',
        'rejection_reason_subject',
        'rejection_reason_description',
    ];

    protected $casts = [
        'content' => 'array',
        'status' => 'integer',
    ];

    public static function getDefaultIncludes(ApiVersion $version): array
    {
        return [];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function publicModel()
    {
        if ($this->type === 'Recipe') {
            return $this->recipe();
        }

        if ($this->type === 'Remedy') {
            return $this->remedy();
        }
    }

    /**
     * UGC hasn't been set up as polymorphic and should be!
     * To aid in eage loading, we have recipes() and remedies()
     * set below and will have to check "type" for the correct
     * value.
     *
     * @todo MAke UGC polymorphic
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'association_id');
    }

    public function remedy()
    {
        return $this->belongsTo(Remedy::class, 'association_id');
    }

    public function approve()
    {
        if ($this->is_public != 1) {
            return false;
        }

        $entityClass = 'App\\' . $this->type;
        $entityInstance = new $entityClass;

        if ($this->association_id > 0) {
            $entityInstance = $entityClass::find($this->association_id);
        }

        if (method_exists($entityInstance, 'approveUserContent')) {
            $entityId = $entityInstance->approveUserContent($this);

            if ($entityId) {
                $this->status = UserGeneratedContentStatus::Accepted;
                $this->association_id = $entityId;

                event(new ApprovedUserGeneratedContent($this));

                return $this->save();
            }
        }

        return false;
    }

    public function reject($subject, $description)
    {
        if ($this->is_public != 1) {
            return false;
        }

        $this->update([
            "public_uuid" => $this->publicModel ? $this->publicModel->uuid : null,
            'status' => UserGeneratedContentStatus::Rejected,
            'rejection_reason_subject' => $subject,
            'rejection_reason_description' => $description,
        ]);

        event(new RejectedUserGeneratedContent($this));
    }

    public function resubmit()
    {
        $this->update([
            'status' => UserGeneratedContentStatus::InReview,
        ]);
    }

    public static function convertToUGC(Model $model)
    {
        $userGeneratedContent = [
            'name' => $model->name,
            'image_url' => $model->image_url,
            'type' => get_class($model) == Recipe::class ?
                'Recipe' :
                'Remedy'
        ];

        $content = [];
        $content['instructions'] = $model->body;
        if ($model->ingredients) {
            $content['ingredients'] = $model->ingredients->map(function ($ingredient) {
                // manage custom
                $newIngredient = [];
                if (!$ingredient->id) {
                    $newIngredient['resource_type'] = 'CustomIngredient';
                } else {
                    $newIngredient['resource_type'] = get_class($ingredient) == Recipe::class ?
                        'Recipe' :
                        'Remedy';
                }

                $ingredientDetails = explode(' ', $ingredient->name);
                $newIngredient['quantity'] = $ingredientDetails[0];
                $newIngredient['measure'] = isset($ingredientDetails[1]) ?
                    $ingredientDetails[1] :
                    '';
                $newIngredient['name'] = isset($ingredientDetails[2]) ?
                    $ingredientDetails[2] : '';

                return $newIngredient;
            });
        }

        // set individual relationships
        if (get_class($model) == Recipe::class) {
            if ($model->categories->isNotEmpty()) {
                $content['recipe_category_id'] = $model->categories->first()->id;
            }

            $userGeneratedContent['content'] = $content;
        } elseif (get_class($model) == Remedy::class) {
            if ($model->bodySystems->isNotEmpty()) {
                $content['body_system_id'] = $model->bodySystems->first()->id;
            }

            if ($model->ailment) {
                $content['ailment_id'] = $model->ailment->id;
            }
        }

        $userGeneratedContent['content'] = $content;

        return $userGeneratedContent;
    }

    public function ailmentUuid(): ?string
    {
        $ailmentId = $this->content['ailment_id'] ?? null;

        if (isUuid($ailmentId)) {
            return $ailmentId;
        }

        $ailment = Ailment::find($ailmentId);
        if ($ailment) {
            return $ailment->uuid;
        }

        return null;
    }

    public function bodySystemUuid(): ?string
    {
        $bodySystemId = $this->content['body_system_id'] ?? null;

        if (isUuid($bodySystemId)) {
            return $bodySystemId;
        }

        $bodySystem = BodySystem::find($bodySystemId);
        if ($bodySystem) {
            return $bodySystem->uuid;
        }

        return null;
    }

    public function recipeCategoryUuid(): ?string
    {
        $recipeCategoryId = $this->content['recipe_category_id'] ?? null;

        if (isUuid($recipeCategoryId)) {
            return $recipeCategoryId;
        }

        $category = Category::find($recipeCategoryId);
        if ($category) {
            return $category->uuid;
        }

        return null;
    }

    public function scopeWhereHasPublicModel(Builder $builder)
    {
        return $builder
            ->where('is_public', true)
            ->where('status', UserGeneratedContentStatus::Accepted)
            ->whereNotNull('association_id');
    }
}
