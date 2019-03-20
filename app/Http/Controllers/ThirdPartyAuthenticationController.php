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
        $keys = array_keys(ThirdPartyPlatformAccount::$typeMap);

        if (!in_array($request->type, $keys, true)) {
            return $this->responseBadRequest('暂不支持此第三方平台登录');
        }

        $driver = \Socialite::driver(ThirdPartyPlatformAccount::$driverMap[$request->type]);
        try {
            $response = $driver->getAccessTokenResponse($request->code);
            $accessToken = array_get($response, 'access_token');
            $oauthUser = $driver->userFromToken($accessToken);
        } catch (\Throwable $throwable) {
            return $this->responseBadRequest('参数错误，无法获取用户信息');
        }

        switch ($request->type) {
            case 'wechat_open_platform':
                $unionId = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;
                $openId = $oauthUser->offsetExists('openid') ? $oauthUser->offsetGet('openid') : null;

                $openPlatformAccount = ThirdPartyPlatformAccount::query()
                    ->where('platform_id', $unionId)
                    ->where('type', $request->type)
                    ->first();
                if ($openPlatformAccount) {
                    $token = auth()->guard()->fromUser($openPlatformAccount->account);

                    return $this->responseJsonWebToken($token);
                }

                $officialAccount = ThirdPartyPlatformAccount::query()
                    ->where('platform_id', $openId)
                    ->where('type', $request->type)
                    ->first();
                if ($officialAccount) {
                    $token = auth()->guard()->fromUser($officialAccount->account);

                    return $this->responseJsonWebToken($token);
                }
                break;
            default:
                return $this->responseInternal('参数错误，暂不支持此第三方平台登录');
                break;
        }


        try {
            DB::transaction(function () use($request, $oauthUser, $unionId, $openId, $accessToken, &$account){
                // 创建账号
                $account = Account::query()->create([
                    'last_login_ip' => $request->ip(),
                    'last_login_at' => now(),
                    'created_with_ip' => $request->ip(),
                ]);

                // 创建用户
                $nickname = $oauthUser->offsetExists('nickname') ? $oauthUser->offsetGet('nickname') : null;
                $avatar = $oauthUser->offsetExists('headimgurl') ? $oauthUser->offsetGet('headimgurl') : null;
                $user = User::query()->create([
                    'account_id' => $account->id,
                    'nickname' => $nickname,
                    'avatar' => $avatar,
                 ]);

                // 创建第三方账号
                if ($unionId) {
                    ThirdPartyPlatformAccount::query()->create([
                        'account_id' => $account->id,
                        'resource_id' => $user->id,
                        'resource_type' => get_class($user),
                        'platform_id' => $unionId,
                        'platform_token' => $accessToken,
                        'type' => $request->type,
                        'nickname' => $nickname,
                        'avatar' => $avatar,
                        'extra' => json_encode($oauthUser->user?:[]),
                    ]);
                }

                if ($openId) {
                    ThirdPartyPlatformAccount::query()->create([
                        'account_id' => $account->id,
                        'resource_id' => $user->id,
                        'resource_type' => get_class($user),
                        'platform_id' => $openId,
                        'platform_token' => $accessToken,
                        'type' => 'wechat_official_account',
                        'nickname' => $nickname,
                        'avatar' => $avatar,
                        'extra' => json_encode($oauthUser->user?:[]),
                    ]);
                }
            });

            return $this->responseJsonWebToken($account);
        } catch (\Throwable $throwable) {
            return $this->responseInternal(transform_error_message($throwable->getMessage(), '用户第三方登录失败'));
        }
    }
}
