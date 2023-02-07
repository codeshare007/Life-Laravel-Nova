<?php

namespace App\Http\Requests\CurrentUser\Settings;

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
            'settings' => 'required|nullable|json',
        ];
    }
}
