<?php

namespace App\Services\CommentService;

use Exception;
use Google\Cloud\Firestore\FirestoreClient;

class FirebaseCommentService implements CommentService
{
    protected $firestore;

    public function __construct()
    {
        $this->firestore = new FirestoreClient([
            'projectId' => 'essentiallife-7ca09',
            'keyFilePath' => storage_path('essentiallife-7ca09-a24994f4f703.json'),
        ]);
    }

    public function delete(string $firebase_document): void
    {
        if (! $this->assertIsComment($firebase_document)) {
            return;
        };

        $this->firestore->document($firebase_document)->delete();
    }

    public function updateBody(string $firebase_document, string $body): void
    {
        if (! $this->assertIsComment($firebase_document)) {
            return;
        };

        $this->firestore->document($firebase_document)->update([
            [
                'path' => 'body',
                'value' => $body,
            ],
        ]);
    }

    protected function assertIsComment(string $firebase_document): bool
    {
        try {
            $data = $this->firestore->document($firebase_document)->snapshot()->data();
    
            if (!$data) {
                return false;
            }
    
            if(!isset($data['body'])) {
                return false;
            }
    
            if(!isset($data['author'])) {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }
}
