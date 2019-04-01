<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'easy_sms' => [
        // HTTP 请求的超时时间（秒）
        'timeout' => 5.0,

        // 默认发送配置
        'default' => [
            // 网关调用策略，默认：顺序调用
            'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

            // 默认可用的发送网关
            'gateways' => ['aliyun'],
        ],
        'gateways' => [
            'errorlog' => [
                'file' => storage_path().'/logs/easy_sms.log',
            ],
            'aliyun' => [
                'access_key_id' => env('ALI_CLOUD_SMS_ACCESS_KEY_ID'),
                'access_key_secret' => env('ALI_CLOUD_SMS_SECRET_ACCESS_KEY'),
                'sign_name' => env('ALI_CLOUD_SMS_SIGN_NAME'),
            ],
        ],
        'default_gateway' => 'aliyun',
        'minutes' => 10,
    ],

    'weixinweb' => [
        'client_id' => env('WECHAT_WEB_APP_ID'),
        'client_secret' => env('WECHAT_WEB_APP_SECRET'),
        'redirect' => env('WECHAT_WEB_REDIRECT_URI'),
    ],

    'qq' => [
        'client_id' => env('QQ_APP_ID'),
        'client_secret' => env('QQ_APP_SECRET'),
        'redirect' => env('QQ_REDIRECT_URI'),
    ],
];
