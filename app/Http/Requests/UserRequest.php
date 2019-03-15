<?php

namespace App\Http\Requests;

use App\Rules\ChinesePhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        return [
            'phone' => ['required', new ChinesePhoneNumber, 'unique:accounts'],
            'key' => ['required', 'string'],
            'code' => ['required', 'numeric', 'digits:6'],
        ];
    }

    public function attributes()
    {
        return [
            'phone' => '手机号码',
            'key' => '短信验证码凭证',
            'code' => '短信验证码',
        ];
    }
}
