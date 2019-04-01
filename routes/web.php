<?php

Route::get('/wechat-login', function () {
    return Socialite::with('weixinweb')->redirect();
});

Route::get('/wechat-callback', function () {
    return view('wechat');
});

Route::get('/qq-login', function () {
    return Socialite::with('qq')->redirect();
});

Route::get('/qq-callback', function () {
    return view('qq');
});