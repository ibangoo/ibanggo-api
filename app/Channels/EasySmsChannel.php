<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;
use Overtrue\EasySms\PhoneNumber;

/**
 * Class EasySmsChannel - 短信发送频道
 *
 * @package App\Channels
 */
class EasySmsChannel
{
    /**
     * 发送短信
     *
     * @param              $notifiable
     * @param Notification $notification
     *
     * @return \InvalidArgumentException | mixed
     */
    public function send($notifiable, Notification $notification)
    {
        try {
            $easySms = app('easySms');
            $parameters = $notification->toEasySms();

            if (!isset($parameters['template']) || empty($parameters['template'])) {
                return new \InvalidArgumentException('短信发送模板不能为空');
            }

            if (!isset($parameters['data']) || empty($parameters['data'])) {
                return new \InvalidArgumentException('短信发送参数不能为空');
            }

            return $easySms->send(new PhoneNumber($notifiable->phone, $notifiable->area_code), $parameters);
        } catch (NoGatewayAvailableException $exception) {
            $code = $exception->getException(config('services.easy_sms.default_gateway'))->getCode();
            $message = $exception->getException(config('services.easy_sms.default_gateway'))->getMessage();

            Log::error('ali_cloud_sms', compact('code', 'message'));
        } finally {
            Log::info('ali_cloud_sms', ['phone' => $notifiable->area_code.$notifiable->phone,]);
        }
    }
}

