<?php

namespace App\Http\Requests\v3_0\Subscription;

use App\Enums\Platform;
use BenSampo\Enum\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class VerifyReceiptRequest extends FormRequest
{
    public function rules()
    {
        return [
            'receipt' => 'required|string',
            'platform' => [new Enum(Platform::class)],
            'user_id' => 'nullable|int',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'platform' => Platform::coerce($this->platform) ?? $this->platform,
        ]);
    }
}
