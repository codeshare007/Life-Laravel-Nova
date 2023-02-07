<?php

namespace App\Services\CommentService;

interface CommentService
{
    public function delete(string $firebase_document): void;
    public function updateBody(string $firebase_document, string $body): void;
}
