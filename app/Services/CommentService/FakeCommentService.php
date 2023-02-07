<?php

namespace App\Services\CommentService;

class FakeCommentService implements CommentService
{  
    public function delete(string $firebase_document): void
    {
    }

    public function updateBody(string $firebase_document, string $body): void
    {
    }
}
