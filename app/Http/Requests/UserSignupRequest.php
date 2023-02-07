<?php

namespace App\Http\Requests;

use App\Avatar;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;

class UserSignupRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'name' => 'required|string|max:50|unique:users',
            'avatar_id' => 'required|int|exists:avatars,id',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'The username has already been taken.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => $this->name ?? $this->defaultName(),
            'avatar_id' => $this->avatar_id ?? $this->randomAvatar(),
        ]);
    }

    private function defaultName()
    {
        return explode('@', $this->email)[0];
    }

    private function randomAvatar()
    {
        $avatar = Avatar::inRandomOrder()->first();

        return $avatar ? $avatar->id : null;
    }

    public function userAttributes(): array
    {
        return array_merge($this->validated(), [
            'password' => Hash::make($this->password),
        ]);
    }
}
