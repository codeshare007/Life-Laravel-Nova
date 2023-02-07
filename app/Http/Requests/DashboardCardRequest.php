<?php

namespace App\Http\Requests;

use App\Enums\Platform;
use BenSampo\Enum\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class DashboardCardRequest extends FormRequest
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
        ];
    }
}
