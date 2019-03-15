<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerificationCodeRequest;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

/**
 * Class VerificationCodeController - 短信验证码控制器
 *
 * @package App\Http\Controllers
 */
class VerificationCodeController extends Controller
{
    /**
     * 发送短信验证码
     *
     * @param VerificationCodeRequest $request
     * @param EasySms                 $easySms
     *
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     * @return mixed
     */
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        // 生成验证码
        $code = str_pad(random_int(1, 999999), 6, 0, STR_PAD_LEFT);
        $phone = $request->phone;

        // 发送短信
        if (app()->environment() === 'local') {
            $code = 123123;
            $result['aliyun']['result']['Code'] = 'OK';
            $result['aliyun']['result']['Message'] = 'OK';
        } else {
            try {
                $result = $easySms->send($phone, ['data' => compact('code'), 'template' => 'SMS_117521917',]);
            } catch (NoGatewayAvailableException $exception) {
                $raw = isset($exception->getExceptions()[config('services.easy_sms.default_gateway')])
                    ? $exception->getExceptions()[config('services.easy_sms.default_gateway')]->raw
                    : null;
                $code = $raw['Code'] ?? 500;
                $message = $raw['Message'] ?? '短信验证码发送失败';

                if ($code === 'isv.BUSINESS_LIMIT_CONTROL') {
                    $message = '短信验证码获取过于频繁，稍后再试。';
                }
                \Log::error('ali_cloud_sms', compact('code', 'message'));

                return $this->responseErrorInternal($message);
            }
        }

        if ($result['aliyun']['result']['Code'] === 'OK' && $result['aliyun']['result']['Message'] === 'OK') {
            // 缓存短信验证码
            $key = 'verification_code_'.str_random(15);
            $expiredAt = now()->addMinutes(config('services.easy_sms.minutes'));
            \Cache::put($key, compact('phone', 'code'), $expiredAt);

            // 记录手机号码获取验证码次数
            \Redis::connection()->incr($phone.'_verification_code');

            return $this->responseArray([
                'key' => $key,
                'expired_at' => $expiredAt->toDateTimeString(),
            ], 201, '验证码已发送，可能会延后，请耐心等待。');
        }

        return $this->responseErrorInternal('短信验证码发送失败');
    }
}
