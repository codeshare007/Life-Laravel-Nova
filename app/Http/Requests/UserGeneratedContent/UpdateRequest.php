<?php

namespace App\Http\Requests\UserGeneratedContent;

use Illuminate\Support\Arr;

class UpdateRequest extends StoreRequest
{
    public function rules()
    {
        return Arr::except(parent::rules(), 'uuid');
    }
}
