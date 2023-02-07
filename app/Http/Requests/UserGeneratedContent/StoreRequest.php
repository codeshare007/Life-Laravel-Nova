<?php

namespace App\Http\Requests\UserGeneratedContent;

use App\Rules\IsNull;
use Illuminate\Foundation\Http\FormRequest;

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
            'uuid' => 'sometimes|nullable|uuid|unique:global_elements,id',
            'name' => 'required',
            'type' => 'required|in:Recipe,Remedy',
            'is_public' => 'required|boolean',
            'content' => 'required|array',
            'base64_image' => 'sometimes|nullable',
            'image_url' => ['sometimes' , new IsNull],
        ];
    }
    
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Prevent the user updating the image_url, only remove it.
        if ($this->image_url !== null) {
            $this->getInputSource()->remove('image_url');
        }
    }
}
