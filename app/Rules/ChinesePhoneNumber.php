<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ChinesePhoneNumber implements Rule
{
    /**
     * Create a new rule instance.
     *
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return (bool)preg_match('/^(\+?0?86\-?)?((13\d|14[57]|15[^4,\D]|17[3678]|18\d)\d{8}|170[059]\d{7})$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return '请输入正确的手机号码';
    }
}
