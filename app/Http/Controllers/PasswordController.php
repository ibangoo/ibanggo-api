<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordRequest;

/**
 * Class PasswordController - 密码控制器
 *
 * @package App\Http\Controllers
 */
class PasswordController extends Controller
{
    /**
     * 设置、忘记、修改密码
     *
     * @param PasswordRequest $request
     *
     * @return mixed
     */
    public function update(PasswordRequest $request)
    {
        $account = $request->user();

        if (!empty($request->old_password)) {
            if (!password_verify($request->old_password, $account->password)) {
                return $this->responseInvalidArgument('旧密码输入错误');
            }

            if (password_verify($request->password, $account->password)) {
                return $this->responseInvalidArgument('新密码与旧密码一致');
            }
        }

        $account->update([
            'password' => bcrypt($request->password),
            'last_login_ip' => $request->ip(),
            'last_login_at' => now(),
        ]);

        return $this->responseJsonWebToken(auth()->guard()->refresh());
    }
}
