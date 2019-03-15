<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class Password implements Rule
{
    protected $tip;
    protected $phone;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($phone)
    {
        $this->phone = $phone;
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
        $length = strlen($value);
        if ($length < 6) {
            $this->tip = '密码长度不能小于 6 位';

            return false;
        }

        if ($length > 15) {
            $this->tip = '密码长度不能大于 15 位';

            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9_\x7f-\xff\.]*$/', $value)) {
            $this->tip = '密码不能包含特殊字符';

            return false;
        }

        $data = \Redis::connection()->hgetall($this->phone.'_password');
        if (isset($data['count']) && $data['count'] >= 5 && now() < Carbon::parse($data['expired_at'])) {
            $this->tip = '您的密码出错已超过 5 次，请修改密码或 20 分钟后重试';

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
        return $this->tip;
    }
}
