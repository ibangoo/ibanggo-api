<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Account;
use App\Models\User;

/**
 * Class UserController - 用户控制器
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * 创建用户
     *
     * @param UserRequest $request
     *
     * @return mixed
     */
    public function store(UserRequest $request)
    {
        // 获取缓存数据
        $data = \Cache::get($request->key);

        if (!$data) {
            return $this->responseNotFound('验证码已过期');
        }

        if (!hash_equals((string)$data['code'], $request->code)) {
            return $this->responseInvalidArgument('验证码错误');
        }

        if (!hash_equals((string)$data['phone'], $request->phone)) {
            return $this->responseInvalidArgument('手机号码错误');
        }

        try {
            \DB::transaction(function () use ($request, &$account) {
                $account = Account::query()->create([
                    'phone' => $request->phone,
                    'login_times' => 1,
                    'last_login_at' => now(),
                    'last_login_ip' => $request->ip(),
                    'created_with_ip' => $request->ip(),
                ]);

                User::query()->create([
                    'account_id' => $account->id,
                    'nickname' => 'ibangoo_'.str_random(6),
                    'avatar' => 'https://iocaffcdn.phphub.org/uploads/images/201710/30/1/TrJS40Ey5k.png',
                ]);
            });
            // 清除缓存
            \Cache::forget($request->key);

            // 生成 Json Web Token
            $data = array_merge($account->toArray(), ['meta' => $this->generateJsonWebToken($account)]);

            return $this->responseArray($data, 201);
        } catch (\Throwable $throwable) {
            return $this->responseInternal('用户注册失败');
        }
    }

    /**
     * 信息更新统一入口
     *
     * @param UserRequest $request
     * @param int         $id
     *
     * @return mixed
     */
    public function update(UserRequest $request, int $id)
    {
        // 授权认证
        if (auth()->id() !== $id) {
            return $this->responseForbidden('权限不足');
        }

        $method = camel_case($request->header(env('API_X_HTTP_REQUEST_METHOD')));
        $parameters = get_request_parameters($request);

        try {
            $this->$method($parameters);

            return $this->responseArray(['id' => $request->user()->id], 201, '更新用户信息成功');
        } catch (\Throwable $throwable) {
            return $this->responseError($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * 更新手机号码
     *
     * @param $parameters
     *
     */
    public function updatePhone($parameters): void
    {
        // 获取缓存数据
        $verifyData = \Cache::get($parameters['key']);
        if (!$verifyData) {
            throw new \InvalidArgumentException('验证码已过期', 404);
        }

        if (!hash_equals((string)$verifyData['code'], $parameters['code'])) {
            throw new \InvalidArgumentException('验证码错误', 422);
        }

        if (!hash_equals((string)$verifyData['phone'], $parameters['phone'])) {
            throw new \InvalidArgumentException('手机号码错误', 422);
        }

        auth()->user()->update(['phone' => $parameters['phone']]);
    }
}
