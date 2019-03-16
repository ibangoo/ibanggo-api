<?php

namespace App\Http\Requests;

use App\Rules\Password;
use Dingo\Api\Http\FormRequest;

class PasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'password' => ['required', new Password($this->user()->phone), 'confirmed'],
            'old_password' => ['nullable', new Password($this->user()->phone)],
        ];

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'password' => '新密码',
            'old_password' => '旧密码',
        ];
    }
}
