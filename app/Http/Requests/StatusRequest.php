<?php

namespace App\Http\Requests;

use App\Enums\Platform;
use BenSampo\Enum\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class StatusRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'platform' => Platform::coerce($this->platform) ?? Platform::iOS(),
            'app_version' => $this->app_version ?? '2.0.4',
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'platform' => ['required', new Enum(Platform::class)],
            'app_version' => 'required',
        ];
    }
}
