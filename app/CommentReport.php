<?php

namespace App;

use App\Enums\CommentReport\Status;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Database\Eloquent\Model;
use App\Enums\CommentReport\ActionTaken;
use App\Services\CommentService\CommentService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentReport extends Model
{
    use CastsEnums;

    protected $casts = [
        'status' => 'int',
        'action_taken' => 'int',
    ];

    protected $enumCasts = [
        'status' => Status::class,
        'action_taken' => ActionTaken::class,
    ];

    protected $guarded = [
        'id',
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function commenter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'commenter_id');
    }

    public function moderateDelete(): self
    {
        $this->update([
            'status' => Status::Resolved(),
            'action_taken' => ActionTaken::Deleted(),
        ]);

        self::getCommentService()->delete($this->firebase_document);
        
        return $this;
    }

    public function moderateReplace(string $replacementText): self
    {
        $this->update([
            'status' => Status::Resolved(),
            'action_taken' => ActionTaken::Replaced(),
        ]);

        self::getCommentService()->updateBody($this->firebase_document, $replacementText);

        return $this;
    }

    public function resolveWithNoAction(): self
    {
        $this->update([
            'status' => Status::Resolved(),
            'action_taken' => ActionTaken::None(),
        ]);

        return $this;
    }

    protected static function getCommentService(): CommentService
    {
        return resolve(CommentService::class);
    }
}
