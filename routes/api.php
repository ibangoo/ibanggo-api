<?php

$api = app(Dingo\Api\Routing\Router::class);

$api->version(config('api.version'), ['namespace' => 'App\Http\Controllers'], function ($api) {

    $api->group([
        'middleware' => 'api.throttle',
        'expires' => 1,
        'limit' => 5,
    ], function ($api) {
        // 短信验证码
        $api->post('verification-codes', 'VerificationCodeController@store')->name('verification_code.store');
    });

    $api->group([
        'middleware' => 'api.throttle',
        'expires' => 1,
        'limit' => 60,
    ], function ($api) {
        // 用户注册
        $api->post('users', 'UserController@store')->name('users.store');

        // 用户登录
        $api->post('authentications', 'AuthenticationController@store')->name('authentications.store');
    });
});