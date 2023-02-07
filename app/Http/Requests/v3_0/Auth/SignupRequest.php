<?php

namespace App\Http\Requests\v3_0\Auth;

use App\Avatar;
use App\Enums\Platform;
use BenSampo\Enum\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use App\Services\Subscriptions\BasePlatform;

class SignupRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'name' => 'required|string|min:3|max:25|unique:users',
            'avatar_id' => 'required|int|exists:avatars,id',
            'receipt' => 'required|string',
            'platform' => [new Enum(Platform::class)],
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
            'platform' => Platform::coerce($this->platform) ?? $this->platform,
        ]);

        if ($this->containsDevEmail()) {
            $this->merge([
                'receipt' => BasePlatform::BYPASS_TOKEN,
            ]);
        }
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

    public function containsDevEmail()
    {
        return ends_with($this->email, '@apple-wqa-dev.test');
    }
}
