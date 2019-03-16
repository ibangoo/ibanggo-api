<?php

namespace App\Http\Requests;

use App\Rules\ChinesePhoneNumber;
use App\Rules\GetVerificationCode;
use Dingo\Api\Http\FormRequest;

class VerificationCodeRequest extends FormRequest
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
        $rules = [];

        switch ($this->route()->getName()) {
            case 'verification_code.store':
                $rules = [
                    'area_code' => ['nullable', 'numeric'],
                    'phone' => ['required', new ChinesePhoneNumber, new GetVerificationCode],
                ];
                break;
            case 'verification_code.check':
                $rules = [
                    'area_code' => ['nullable', 'numeric'],
                    'phone' => ['required', new ChinesePhoneNumber, 'exists:accounts,phone', new GetVerificationCode],
                    'key' => ['required', 'string'],
                    'code' => ['required', 'numeric', 'digits:6'],
                ];
                break;
        }

        return $rules;

    }

    public function attributes(): array
    {
        return [
            'area_code' => '区号',
            'phone' => '手机号码',
            'key' => '短信验证码凭证',
            'code' => '短信验证码',
        ];
    }
}
