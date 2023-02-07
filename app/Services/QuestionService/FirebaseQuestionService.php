<?php

namespace App\Services\QuestionService;

use Exception;
use App\Question;
use Illuminate\Support\Facades\App;
use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\CollectionReference;

class FirebaseQuestionService implements QuestionService
{
    protected $firestore;

    public function __construct()
    {
        $this->firestore = new FirestoreClient([
            'projectId' => 'essentiallife-7ca09',
            'keyFilePath' => storage_path('essentiallife-7ca09-a24994f4f703.json'),
        ]);
    }

    protected function collection(): CollectionReference
    {
        $collectionName = 'staging_questions';

        if (App::environment('production')) {
            $collectionName = 'questions';
        }

        return $this->firestore->collection($collectionName);
    }

    public function insert(Question $question): void
    {
        $documentRef = $this->collection()->add([
            'author' => $question->user->firebase_id,
            'title' => $question->title,
            'body' => $question->description,
            'created' => $question->created_at,
            'flagged' => false,
            'likes' => 0,
            'reply_count' => 0,
        ]);

        $question->update([
            'firebase_document' => $documentRef->path(),
        ]);
    }

    public function update(Question $question): void
    {
        if ($question->isDirty(['title', 'description'])) {
            $this->firestore
                ->document($question->firebase_document)
                ->update([
                    [
                        'path' => 'title',
                        'value' => $question->title,
                    ],
                    [
                        'path' => 'body',
                        'value' => $question->description,
                    ],
                ]);
        }
    }
    
    public function delete(Question $question): void
    {
        $this->firestore->document($question->firebase_document)->delete();
    }
}
