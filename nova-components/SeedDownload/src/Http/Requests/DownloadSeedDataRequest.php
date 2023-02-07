<?php

namespace Wqa\SeedDownload\Http\Requests;

use App\Enums\UserLanguage;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class DownloadSeedDataRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'language' => ['required', 'string', new EnumValue(UserLanguage::class)],
        ];
    }

    public function language()
    {
        return UserLanguage::getInstance($this->language);
    }
}
