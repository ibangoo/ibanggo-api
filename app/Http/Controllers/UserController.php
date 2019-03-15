<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Account;
use App\Models\User;

class UserController extends Controller
{
    public function store(UserRequest $request)
    {
        // 获取缓存数据
        $data = \Cache::get($request->key);

        if (!$data) {
            return $this->responseErrorNotFound('验证码已过期');
        }

        if (!hash_equals((string)$data['code'], $request->code)) {
            return $this->responseErrorInvalidArgument('验证码错误');
        }

        if (!hash_equals((string)$data['phone'], $request->phone)) {
            return $this->responseErrorInvalidArgument('手机号码错误');
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
            return $this->responseErrorInternal('用户注册失败');
        }
    }
}
