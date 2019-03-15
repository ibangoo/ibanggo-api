<?php

namespace App\Http\Requests;

use App\Rules\ChinesePhoneNumber;
use App\Rules\GetVerificationCode;
use App\Rules\Password;
use Dingo\Api\Http\FormRequest;

class AuthenticationRequest extends FormRequest
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
            'phone' => ['required', new ChinesePhoneNumber, 'exists:accounts,phone'],
            'password' => ['required_without:code', new Password($this->phone)],
            'code' => ['required_without:password', 'numeric', 'digits:6', new GetVerificationCode],
            'key' => ['required_without:password', 'string']
        ];
    }

    public function attributes(): array
    {
        return [
            'phone'=> '手机号码',
            'code' => '短信验证码',
            'key' => '短信验证码凭证'
        ];
    }
}
