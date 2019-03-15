<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GetVerificationCode implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {

        $times = \Redis::connection()->get($value.'_verification_code');

        if ($times === null) {
            \Redis::connection()->set($value.'_verification_code', 0, 'EX', 60 * 60);
        } elseif ($times >= 4) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return '验证码获取过于频繁，请稍后再试。';
    }
}
