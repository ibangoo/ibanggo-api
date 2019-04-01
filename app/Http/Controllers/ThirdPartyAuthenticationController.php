<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThirdPartyAuthenticationRequest;
use App\Models\Account;
use App\Models\ThirdPartyPlatformAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ThirdPartyAuthenticationController extends Controller
{
    public function store(ThirdPartyAuthenticationRequest $request)
    {
        // 判断是否支持当前第三方平台
        $keys = array_keys(ThirdPartyPlatformAccount::$typeMap);
        if (!in_array($request->type, $keys, true)) {
            return $this->responseBadRequest('暂不支持当前第三方平台登录');
        }

        // 根据 code 获取 access_token，根据 access_token 获取用户信息
        $driver = \Socialite::driver(ThirdPartyPlatformAccount::$driverMap[$request->type]);
        try {
            $response = $driver->getAccessTokenResponse($request->code);
            $accessToken = array_get($response, 'access_token');
            $oauthUser = $driver->userFromToken($accessToken);
        } catch (\Throwable $throwable) {
            return $this->responseBadRequest('参数错误，无法获取用户信息');
        }

        // 根据当前第三方平台类型做特殊业务处理
        switch ($request->type) {
            case 'wechat_open_platform':
                $openid = $oauthUser->id ?? null;
                $unionId = $oauthUser->user['unionid'] ?? null;

                $avatar = $oauthUser->avatar ?? null;
                $nickname = $oauthUser->nickname ?? null;
                break;
            case 'qq':
                $openid = null;
                $unionId = $oauthUser->id ?? null;

                $avatar = $oauthUser->avatar ?? null;
                $nickname = $oauthUser->nickname ?? null;
                break;
            default:
                return $this->responseInternal('参数错误，暂不支持此第三方平台登录');
                break;
        }

        // 所有平台统一根据 unionid 获取用户信息
        $openPlatformAccount = ThirdPartyPlatformAccount::query()
            ->where('platform_id', $unionId)
            ->where('type', $request->type)
            ->first();
        if ($openPlatformAccount) {
            $token = auth()->guard()->fromUser($openPlatformAccount->account);

            return $this->responseJsonWebToken($token);
        }

        // 没有 unionid,则获取 openid 获取用户信息
        if ($openid) {
            $openPlatformAccount = ThirdPartyPlatformAccount::query()
                ->where('platform_id', $openid)
                ->where('type', $request->type)
                ->first();
            if ($openPlatformAccount) {
                $token = auth()->guard()->fromUser($openPlatformAccount->account);

                return $this->responseJsonWebToken($token);
            }
        }

        try {
            DB::transaction(function () use ($request, $oauthUser, $unionId, $accessToken, &$account, $nickname, $avatar, $openid) {
                // 创建账号
                $account = Account::query()->create([
                    'last_login_at' => now(),
                    'last_login_ip' => $request->ip(),
                    'created_with_ip' => $request->ip(),
                ]);

                $user = User::query()->create([
                    'account_id' => $account->id,
                    'nickname' => $nickname,
                    'avatar' => $avatar,
                ]);

                ThirdPartyPlatformAccount::query()->create([
                    'account_id' => $account->id,
                    'resource_id' => $user->id,
                    'resource_type' => get_class($user),
                    'platform_id' => $unionId,
                    'platform_token' => $accessToken,
                    'type' => $request->type,
                    'nickname' => $nickname,
                    'avatar' => $avatar,
                    'extra' => json_encode($oauthUser->user ?: []),
                ]);

                if ($openid) {
                    ThirdPartyPlatformAccount::query()->create([
                        'account_id' => $account->id,
                        'resource_id' => $user->id,
                        'resource_type' => get_class($user),
                        'platform_id' => $openid,
                        'platform_token' => $accessToken,
                        'type' => $request->type,
                        'nickname' => $nickname,
                        'avatar' => $avatar,
                        'extra' => $request->type.'_openid',
                    ]);
                }
            });

            return $this->responseJsonWebToken(auth()->guard()->fromUser($account));
        } catch (\Throwable $throwable) {
            return $this->responseInternal(transform_error_message($throwable->getMessage(), '用户第三方登录失败'));
        }
    }
}
