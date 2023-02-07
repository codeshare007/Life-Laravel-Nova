<?php

namespace App\Http\Requests\ContentSuggestion;

use BenSampo\Enum\Rules\EnumKey;
use BenSampo\Enum\Rules\EnumValue;
use App\Enums\ContentSuggestionMode;
use App\Enums\ContentSuggestionType;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\ContentSuggestionAssociationType;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'type' => ['required', new EnumValue(ContentSuggestionType::class)],
            'mode' => ['required', new EnumValue(ContentSuggestionMode::class)],
            'content' => '',
            'association_type' => ['required_with:association_id', 'nullable', new EnumKey(ContentSuggestionAssociationType::class)],
            'association_id' => 'required_with:association_type|nullable|string',
        ];
    }
}
