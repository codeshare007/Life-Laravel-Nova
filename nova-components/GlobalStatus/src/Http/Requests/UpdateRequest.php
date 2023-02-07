<?php

namespace Wqa\GlobalStatus\Http\Requests;

use App\Enums\Platform;
use App\Enums\StatusType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'platform' => ['required', new EnumValue(Platform::class)],
            'type' => ['required', new EnumValue(StatusType::class)],
            'title' => 'nullable|string',
            'message' => 'nullable|string',
            'button_text' => 'nullable|string',
            'button_url' => 'nullable|string|url',
        ];
    }
}
