<?php

$api = app(Dingo\Api\Routing\Router::class);

$api->version(config('api.version'), ['namespace' => 'App\Http\Controllers'], function ($api) {
    $api->get('/', function () {
        return response()->json(['version' => config('api.version')]);
    });

    $api->group([
        'middleware' => 'api.throttle',
        'expires' => 1,
        'limit' => 5,
    ], function ($api) {
        // 发送短信验证码
        $api->post('verification-codes', 'VerificationCodeController@store')->name('verification_code.store');

        // 验证短信验证码
        $api->delete('verification-codes', 'VerificationCodeController@check')->name('verification_code.check');
    });

    $api->group([
        'middleware' => 'api.throttle',
        'expires' => 1,
        'limit' => 60,
    ], function ($api) {
        // 用户注册
        $api->post('users', 'UserController@store')->name('users.store');

        // 登录认证
        $api->post('authentications', 'AuthenticationController@store')->name('authentications.store');
    });

    $api->group([
        'middleware' => ['api.throttle', 'auth'],
        'expires' => 1,
        'limit' => 60,
    ], function ($api) {
        // 刷新 Token
        $api->patch('authentications', 'AuthenticationController@update')->name('authentications.update');

        // 注销 Token
        $api->delete('authentications', 'AuthenticationController@destroy')->name('authentications.destroy');

        // 设置密码、忘记、修改密码
        $api->patch('passwords', 'PasswordController@update')->name('passwords.update');

        // 用户信息更新
        $api->patch('users/{id}', 'UserController@update')->name('users.update');
    });
});