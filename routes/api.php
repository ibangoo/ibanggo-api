<?php

$api = app(Dingo\Api\Routing\Router::class);

$api->version(config('api.version'), ['namespace' => 'App\Http\Controllers'], function ($api) {

    $api->group([
        'middleware' => 'api.throttle',
        'expires' => 1,
        'limit' => 60,
    ], function ($api) {
        // 短信验证码
        $api->post('verification-codes', 'VerificationCodeController@store')->name('verification_code.store');
    });
});