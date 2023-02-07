<?php

namespace App;

use Illuminate\Support\Str;
use App\Enums\Question\Status;
use BenSampo\Enum\Traits\CastsEnums;
use App\Events\QuestionApprovedEvent;
use App\Events\QuestionRejectedEvent;
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\EloquentSortable\SortableTrait;
use App\Services\QuestionService\QuestionService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model implements Sortable
{
    use SortableTrait;
    use CastsEnums;
    
    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'status' => 'int',
    ];

    protected $enumCasts = [
        'status' => Status::class,
    ];

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function (Question $question) {
            $question->update([
                'uuid' => $question->uuid ?? Str::uuid(),
            ]);
            
            $question->refresh();
            $question->approve();
        });

        static::deleting(function (Question $question) {
            if ($question->firebase_document) {
                self::getQuestionService()->delete($question);
            }
        });

        static::updating(function (Question $question) {
            if ($question->firebase_document) {
                self::getQuestionService()->update($question);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', Status::Approved);
    }

    public function approve(): self
    {
        if ($this->status->isNot(Status::InReview)) {
            return $this;
        }

        self::getQuestionService()->insert($this);

        $this->update([
            'status' => Status::Approved(),
        ]);

        event(new QuestionApprovedEvent($this));

        return $this;
    }

    public function unapprove(): self
    {
        if ($this->status->isNot(Status::Approved)) {
            return $this;
        }

        self::getQuestionService()->delete($this);

        $this->update([
            'status' => Status::InReview(),
        ]);

        return $this;
    }

    public function reject($subject, $description): self
    {
        if ($this->status->isNot(Status::InReview)) {
            return $this;
        }

        $this->update([
            'status' => Status::Rejected(),
            'rejection_reason_subject' => $subject,
            'rejection_reason_description' => $description,
        ]);

        event(new QuestionRejectedEvent($this));

        return $this;
    }

    protected static function getQuestionService(): QuestionService
    {
        return resolve(QuestionService::class);
    }
}
