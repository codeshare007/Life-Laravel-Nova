<?php

namespace App\Services\QuestionService;

use App\Question;

class FakeQuestionService implements QuestionService
{
    public function insert(Question $question): void
    {
    }

    public function update(Question $question): void
    {
    }
    
    public function delete(Question $question): void
    {
    }
}
