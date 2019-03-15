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
        return [
            'area_code' => ['nullable', 'numeric'],
            'phone' => ['required', new ChinesePhoneNumber, 'unique:accounts', new GetVerificationCode],
        ];
    }

    public function attributes(): array
    {
        return [
            'area_code' => '区号',
            'phone' => '手机号码',
        ];
    }
}
