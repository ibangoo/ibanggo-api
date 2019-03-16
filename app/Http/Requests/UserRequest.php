<?php

namespace App\Http\Requests;

use App\Rules\ChinesePhoneNumber;
use Dingo\Api\Http\FormRequest;

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
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [];
        switch ($this->route()->getName()) {
            case 'users.store':
                $rules = [
                    'phone' => ['required', new ChinesePhoneNumber, 'unique:accounts'],
                    'key' => ['required', 'string'],
                    'code' => ['required', 'numeric', 'digits:6'],
                ];
                break;
            case 'users.update':
                switch ($this->header(env('API_X_HTTP_REQUEST_METHOD'))) {
                    case 'update_phone':
                        $rules = [
                            'phone' => ['required', new ChinesePhoneNumber, 'unique:accounts'],
                            'key' => ['required', 'string'],
                            'code' => ['required', 'numeric', 'digits:6'],
                        ];
                        break;
                    default:
                        throw new \InvalidArgumentException('Bad Request', 400);
                        break;
                }

                break;
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'phone' => '手机号码',
            'key' => '短信验证码凭证',
            'code' => '短信验证码',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.unique' => '该手机号码已绑定其他账号',
        ];
    }
}
