<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticationRequest;
use App\Models\Account;

/**
 * Class AuthenticationController
 *
 * @package App\Http\Controllers
 */
class AuthenticationController extends Controller
{
    /**
     * 用户登录
     *
     * @param AuthenticationRequest $request
     *
     * @return mixed
     */
    public function store(AuthenticationRequest $request)
    {
        // 密码登录
        if ($request->password) {
            $cacheKey = $request->phone.'_password';
            $credentials['phone'] = $request->phone;
            $credentials['password'] = $request->password;

            if (!$token = auth()->guard()->attempt($credentials)) {
                // 记录密码输出错误
                $redis = \Redis::connection();
                $redis->hincrby($cacheKey, 'count', 1);

                // 判断密码输出错误是否超过 5 次，超过 5 次后输入错误密码需要 20 分钟后才能再输入一次
                if ($redis->hget($cacheKey, 'count') >= 5) {
                    $redis->hset($cacheKey, 'expired_at', now()->addMinutes(20)->toDateTimeString());
                }

                return $this->response->errorUnauthorized('手机号码或密码错误');
            }

            // 清除密码错误缓存
            \Redis::connection()->hdel($cacheKey, ['count', 'expired_at']);

            return $this->responseJsonWebToken($token);
        }

        // 验证码登录
        if ($request->code) {
            // 获取缓存数据
            $data = \Cache::get($request->key);

            if (!$data) {
                return $this->responseErrorNotFound('验证码已过期');
            }

            if (!hash_equals((string)$data['code'], $request->code)) {
                return $this->responseErrorInvalidArgument('手机号码或验证码错误');
            }

            if (!hash_equals((string)$data['phone'], $request->phone)) {
                return $this->responseErrorInvalidArgument('手机号码或验证码错误');
            }

            // 查询用户
            if (!$user = Account::query()->where(['phone' => $request->phone])->first()) {
                return $this->responseErrorNotFound('手机号码或验证码错误');
            }

            // 删除缓存
            \Cache::forget($request->key);

            // 响应令牌
            return $this->responseJsonWebToken(auth()->guard()->fromUser($user));
        }
    }

    /**
     * Json Web Token 刷新
     *
     * @return mixed
     */
    public function update()
    {
        $token = auth()->guard()->refresh();

        return $this->responseJsonWebToken($token);
    }

    /**
     * Json Web Token 注销
     *
     * @return \Dingo\Api\Http\Response
     */
    public function destroy()
    {
        auth()->guard()->logout();

        return $this->response->noContent();
    }
}
