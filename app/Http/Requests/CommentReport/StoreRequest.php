<?php

namespace App\Http\Requests\CommentReport;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'reporter_id' => $this->user()->id,
            'element_uuid' => $this->element ?? '',
            'firebase_document' => trim($this->firebase_document, '/'),
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
            'reporter_id' => 'required|int|exists:users,id',
            'commenter_id' => 'required|int|exists:users,id',
            'reason' => 'required|string|max:400',
            'comment' => 'required|string',
            'element_uuid' => 'sometimes|string',
            'firebase_document' => 'required|string',
        ];
    }
}
