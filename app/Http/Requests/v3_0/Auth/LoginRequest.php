<?php

namespace App\Http\Requests\v3_0\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }

    public function credentials(): array
    {
        return $this->only(['email', 'password']);
    }
}
